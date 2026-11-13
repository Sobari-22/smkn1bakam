<?php

namespace App\Support;

/**
 * Konversi teks biasa <-> HTML aman untuk isi berita.
 *
 * Admin menulis teks biasa (paragraf dipisah baris kosong). Saat disimpan, semua
 * karakter di-escape lalu dibungkus <p>, sehingga tidak ada HTML/script yang bisa
 * lolos ke halaman publik (yang menampilkan isi berita dengan {!! !!}).
 */
class RichText
{
    public static function toHtml(string $text): string
    {
        $paragraphs = preg_split('/\R{2,}/', trim($text)) ?: [];

        return collect($paragraphs)
            ->map(fn ($p) => trim($p))
            ->filter()
            ->map(fn ($p) => '<p>'.nl2br(e($p), false).'</p>')
            ->implode("\n");
    }

    public static function toPlain(?string $html): string
    {
        $text = preg_replace('#</p>\s*<p[^>]*>#i', "\n\n", (string) $html);
        $text = preg_replace('#<br\s*/?>#i', "\n", (string) $text);

        return trim(html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
