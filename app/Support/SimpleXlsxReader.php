<?php

namespace App\Support;

use RuntimeException;
use ZipArchive;

/**
 * Pembaca file .xlsx / .csv sederhana tanpa library tambahan (pasangan dari
 * SimpleXlsx yang menulis). Membaca sheet pertama dan mengembalikan array
 * baris, kunci array = nomor baris asli (mulai 1, baris kosong dilewati) dan
 * tiap baris berupa array string yang diindeks 0,1,2,... sesuai kolom.
 * Butuh ekstensi PHP "zip" dan "simplexml" untuk .xlsx.
 */
class SimpleXlsxReader
{
    private const REL_NS = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    /** @return array<int, array<int, string>> */
    public static function read(string $path, ?string $extension = null): array
    {
        $extension = strtolower($extension ?? pathinfo($path, PATHINFO_EXTENSION));

        return $extension === 'csv' ? self::readCsv($path) : self::readXlsx($path);
    }

    private static function readCsv(string $path): array
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException('File tidak dapat dibaca.');
        }

        // Lewati BOM UTF-8 (CSV dari Excel biasanya memilikinya)
        if (fread($handle, 3) !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Excel versi Indonesia menyimpan CSV dengan pemisah titik koma
        $firstLine = (string) fgets($handle);
        $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
        rewind($handle);
        if (fread($handle, 3) !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $rows = [];
        $lineNumber = 0;

        while (($data = fgetcsv($handle, 0, $delimiter, '"', '\\')) !== false) {
            $lineNumber++;

            if ($data === [null]) {
                continue; // baris kosong
            }

            $rows[$lineNumber] = array_map(fn ($v) => trim((string) $v), $data);
        }

        fclose($handle);

        return $rows;
    }

    private static function readXlsx(string $path): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Ekstensi PHP "zip" belum aktif.');
        }

        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw new RuntimeException('File Excel tidak valid atau rusak. Pastikan formatnya .xlsx.');
        }

        $shared = self::sharedStrings($zip);
        $sheetXml = $zip->getFromName(self::firstSheetPath($zip));
        $zip->close();

        if ($sheetXml === false) {
            throw new RuntimeException('Sheet pertama pada file Excel tidak ditemukan.');
        }

        $sheet = @simplexml_load_string($sheetXml);

        if ($sheet === false || ! isset($sheet->sheetData)) {
            throw new RuntimeException('Isi sheet pada file Excel tidak dapat dibaca.');
        }

        $rows = [];

        foreach ($sheet->sheetData->row as $row) {
            $rowNumber = (int) $row['r'] ?: count($rows) + 1;
            $cells = [];
            $position = 0;

            foreach ($row->c as $c) {
                $ref = (string) $c['r'];
                $index = $ref !== '' ? self::colIndex($ref) : $position;
                $position = $index + 1;

                $cells[$index] = trim(self::cellValue($c, $shared));
            }

            if ($cells === []) {
                continue;
            }

            $line = [];
            for ($i = 0, $max = max(array_keys($cells)); $i <= $max; $i++) {
                $line[$i] = $cells[$i] ?? '';
            }

            $rows[$rowNumber] = $line;
        }

        return $rows;
    }

    private static function cellValue(\SimpleXMLElement $c, array $shared): string
    {
        $type = (string) $c['t'];

        if ($type === 's') {
            return $shared[(int) $c->v] ?? '';
        }

        if ($type === 'inlineStr') {
            if (isset($c->is->t)) {
                return (string) $c->is->t;
            }

            $text = '';
            foreach ($c->is->r as $run) {
                $text .= (string) $run->t;
            }

            return $text;
        }

        $value = (string) $c->v;

        if ($type === 'b') {
            return $value === '1' ? '1' : '0';
        }

        // Angka panjang (mis. NIP yang tersimpan sebagai angka) jangan tampil 1.98E+17
        if ($value !== '' && is_numeric($value) && stripos($value, 'E') !== false) {
            return sprintf('%.0f', (float) $value);
        }

        return $value;
    }

    private static function sharedStrings(ZipArchive $zip): array
    {
        $raw = $zip->getFromName('xl/sharedStrings.xml');

        if ($raw === false) {
            return [];
        }

        $xml = @simplexml_load_string($raw);
        $strings = [];

        if ($xml === false) {
            return [];
        }

        foreach ($xml->si as $si) {
            if (isset($si->t)) {
                $strings[] = (string) $si->t;
                continue;
            }

            $text = '';
            foreach ($si->r as $run) {
                $text .= (string) $run->t;
            }
            $strings[] = $text;
        }

        return $strings;
    }

    private static function firstSheetPath(ZipArchive $zip): string
    {
        $fallback = 'xl/worksheets/sheet1.xml';

        $workbook = $zip->getFromName('xl/workbook.xml');
        $rels = $zip->getFromName('xl/_rels/workbook.xml.rels');

        if ($workbook === false || $rels === false) {
            return $fallback;
        }

        $wb = @simplexml_load_string($workbook);
        $rl = @simplexml_load_string($rels);

        if ($wb === false || $rl === false || ! isset($wb->sheets->sheet[0])) {
            return $fallback;
        }

        $rid = (string) $wb->sheets->sheet[0]->attributes(self::REL_NS)['id'];

        foreach ($rl->Relationship as $rel) {
            if ((string) $rel['Id'] === $rid) {
                $target = ltrim((string) $rel['Target'], '/');

                return str_starts_with($target, 'xl/') ? $target : 'xl/'.$target;
            }
        }

        return $fallback;
    }

    /** "C5" -> 2 (indeks kolom berbasis 0) */
    private static function colIndex(string $ref): int
    {
        preg_match('/^([A-Za-z]+)/', $ref, $m);
        $letters = strtoupper($m[1] ?? 'A');
        $index = 0;

        for ($i = 0, $len = strlen($letters); $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - 64);
        }

        return $index - 1;
    }
}
