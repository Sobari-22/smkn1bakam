<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concentration;
use App\Models\Teacher;
use App\Models\TeacherPosition;
use App\Support\SimpleXlsx;
use App\Support\SimpleXlsxReader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class TeacherAdminController extends Controller
{
    public function index(): View
    {
        $teachers = Teacher::with(['positions', 'concentrations'])->orderBy('name')->paginate(15);
        $concentrations = Concentration::orderBy('id')->get(['id', 'name', 'title']);

        $positionDefs = config('struktur.positions');

        // Dropdown jabatan dikelompokkan sesuai config/struktur.php
        $positionGroups = collect($positionDefs)
            ->map(fn ($def, $key) => $def + ['key' => $key])
            ->groupBy('group');

        // Jabatan yang hanya boleh satu orang dan sudah terisi (untuk menandai di dropdown)
        $taken = TeacherPosition::with('teacher:id,name')->get()
            ->filter(fn ($p) => ($positionDefs[$p->position_key]['single'] ?? false) === true)
            ->mapWithKeys(fn ($p) => [$p->position_key => ['id' => $p->teacher_id, 'name' => $p->teacher->name ?? '-']])
            ->all();

        return view('admin.teachers.index', compact('teachers', 'concentrations', 'positionDefs', 'positionGroups', 'taken'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('new', $this->rules());

        $attrs = [
            'name' => trim($data['name']),
            'nip' => filled($data['nip'] ?? null) ? trim($data['nip']) : null,
            'tmt' => $data['tmt'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('photo')) {
            $attrs['photo'] = $request->file('photo')->store('guru', 'public');
        }

        try {
            $teacher = $this->persist(null, $attrs, $this->cleanSubjects($data['subjects'] ?? []),
                $request->boolean('teaches_all_programs'), array_map('intval', $data['concentrations'] ?? []),
                array_values($data['positions'] ?? []), $data['position_classes'] ?? []);
        } catch (ValidationException $e) {
            if (isset($attrs['photo'])) {
                Storage::disk('public')->delete($attrs['photo']);
            }

            throw $e->errorBag('new');
        }

        return back()->with('success', 'Data '.$teacher->name.' berhasil ditambahkan.');
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $bag = 'teacher-'.$teacher->id;
        $data = $request->validateWithBag($bag, $this->rules());

        $attrs = [
            'name' => trim($data['name']),
            'nip' => filled($data['nip'] ?? null) ? trim($data['nip']) : null,
            'tmt' => $data['tmt'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        $oldPhoto = $teacher->photo;

        if ($request->hasFile('photo')) {
            $attrs['photo'] = $request->file('photo')->store('guru', 'public');
        }

        try {
            $this->persist($teacher, $attrs, $this->cleanSubjects($data['subjects'] ?? []),
                $request->boolean('teaches_all_programs'), array_map('intval', $data['concentrations'] ?? []),
                array_values($data['positions'] ?? []), $data['position_classes'] ?? []);
        } catch (ValidationException $e) {
            if (isset($attrs['photo'])) {
                Storage::disk('public')->delete($attrs['photo']);
            }

            throw $e->errorBag($bag);
        }

        if (isset($attrs['photo']) && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return back()->with('success', 'Data '.$teacher->name.' berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete(); // jabatan & relasi konsentrasi ikut terhapus (cascade)

        return back()->with('success', 'Data guru/staf berhasil dihapus.');
    }

    /* ------------------------------------------------------------------ */
    /*  Import / template Excel                                            */
    /* ------------------------------------------------------------------ */

    public function template(): BinaryFileResponse
    {
        return SimpleXlsx::download(
            'template-import-guru.xlsx',
            ['Nama Lengkap', 'NIP', 'TMT', 'Jabatan', 'Mata Pelajaran', 'Mengajar di', 'Status'],
            [
                ['Contoh Nama, S.Pd.', '198501012010011001', '2010-01-01', 'Wakasek kesiswaan; Pembina harian OSIS', 'Informatika; Dasar teknik jaringan komputer', 'TKJ; TKR', 'Aktif'],
                ['Contoh Guru Mapel, S.Pd.', '', '', '', 'Matematika', 'Semua program', 'Aktif'],
                ['Contoh Wali Kelas, S.Kom.', '', '', 'Wali kelas TKJ (XI TKJ 1)', 'Administrasi sistem jaringan', 'TKJ', 'Aktif'],
                ['Contoh Kepala Sekolah, M.Pd.', '', '', 'Kepala sekolah', '', '', 'Aktif'],
            ],
            'Guru & Staf'
        );
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:2048'],
        ], [
            'file.required' => 'Pilih file Excel (.xlsx) atau CSV terlebih dahulu.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
        ], ['file' => 'file']);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['xlsx', 'csv'], true)) {
            return back()->withErrors(['file' => 'Format file harus .xlsx atau .csv.']);
        }

        try {
            $rows = SimpleXlsxReader::read($file->getRealPath(), $extension);
        } catch (RuntimeException $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors(['file' => 'File tidak dapat dibaca. Gunakan template yang disediakan.']);
        }

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'File kosong atau hanya berisi baris judul kolom.']);
        }

        if (count($rows) > 1001) {
            return back()->withErrors(['file' => 'Maksimal 1000 baris data per file.']);
        }

        $aliases = [
            'name' => ['nama', 'namalengkap'],
            'nip' => ['nip'],
            'tmt' => ['tmt', 'tanggalmulaitugas'],
            'positions' => ['jabatan', 'jabatanstruktural'],
            'subjects' => ['matapelajaran', 'mapel'],
            'teaching' => ['mengajardi', 'mengajar', 'programkeahlian', 'program'],
            'status' => ['status', 'statusaktif'],
        ];

        $columns = [];
        foreach ($rows[array_key_first($rows)] as $index => $heading) {
            $key = $this->norm((string) $heading);
            foreach ($aliases as $field => $names) {
                if (in_array($key, $names, true) && ! isset($columns[$field])) {
                    $columns[$field] = $index;
                }
            }
        }

        if (! isset($columns['name'])) {
            return back()->withErrors(['file' => 'Kolom "Nama Lengkap" tidak ditemukan di baris pertama. Gunakan template yang disediakan.']);
        }

        // Peta untuk mencocokkan teks di Excel ke data sistem
        $defs = config('struktur.positions');
        $labelToKey = [];
        foreach ($defs as $key => $def) {
            $labelToKey[$this->norm($def['label'])] = $key;
            $labelToKey[$this->norm($key)] = $key;
        }

        $allConcentrations = Concentration::all(['id', 'name', 'slug']);
        $concentrationMap = [];
        foreach ($allConcentrations as $c) {
            $concentrationMap[$this->norm($c->name)] = $c->id;
            $concentrationMap[$this->norm($c->slug)] = $c->id;
        }

        $cell = fn (array $row, string $field): string => isset($columns[$field]) ? trim((string) ($row[$columns[$field]] ?? '')) : '';
        $split = fn (string $text): array => array_values(array_filter(array_map('trim', preg_split('/[;\n]+/', $text) ?: [])));

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $issues = [];

        foreach (array_slice($rows, 1, null, true) as $line => $row) {
            if (implode('', $row) === '') {
                continue;
            }

            $name = $cell($row, 'name');
            $nip = $cell($row, 'nip');

            $validator = Validator::make(['name' => $name, 'nip' => $nip], [
                'name' => ['required', 'string', 'max:255'],
                'nip' => ['nullable', 'string', 'max:50'],
            ], [], ['name' => 'Nama', 'nip' => 'NIP']);

            if ($validator->fails()) {
                $skipped++;
                $issues[] = "Baris {$line} dilewati: ".$validator->errors()->first();
                continue;
            }

            // Jabatan: hanya diproses bila kolom Jabatan ada di file
            $keys = null;
            $classes = [];
            if (isset($columns['positions'])) {
                $keys = [];
                foreach ($split($cell($row, 'positions')) as $token) {
                    $class = null;
                    if (preg_match('/^(.*?)\s*\((.+)\)\s*$/u', $token, $m)) {
                        $token = $m[1];
                        $class = trim($m[2]);
                    }

                    $key = $labelToKey[$this->norm($token)] ?? null;

                    if ($key === null) {
                        $issues[] = "Baris {$line}: jabatan \"{$token}\" tidak dikenali, dilewati.";
                        continue;
                    }

                    $keys[] = $key;
                    if ($class !== null && ($defs[$key]['class'] ?? false)) {
                        $classes[$key] = Str::limit($class, 50, '');
                    }
                }
            }

            // Mengajar di
            $allPrograms = false;
            $concIds = [];
            foreach (preg_split('/[;,\n]+/', $cell($row, 'teaching')) ?: [] as $token) {
                $token = trim($token);
                if ($token === '') {
                    continue;
                }

                if (str_starts_with($this->norm($token), 'semua')) {
                    $allPrograms = true;
                } elseif (isset($concentrationMap[$this->norm($token)])) {
                    $concIds[] = $concentrationMap[$this->norm($token)];
                } else {
                    $issues[] = "Baris {$line}: \"{$token}\" pada kolom Mengajar di tidak dikenali, dilewati.";
                }
            }

            $tmtText = $cell($row, 'tmt');
            $tmt = $this->parseDate($tmtText);
            if ($tmtText !== '' && $tmt === null) {
                $issues[] = "Baris {$line}: format TMT \"{$tmtText}\" tidak dikenali, dikosongkan (gunakan YYYY-MM-DD).";
            }

            $statusText = mb_strtolower($cell($row, 'status'));
            $isActive = ! in_array($statusText, ['nonaktif', 'non-aktif', 'non aktif', 'tidak aktif', 'tidak', 'no', 'n', '0'], true);

            // Cocokkan dengan data yang sudah ada: NIP, atau nama bila NIP kosong
            if ($nip !== '') {
                $teacher = Teacher::where('nip', $nip)->first();
            } else {
                $teacher = Teacher::where(fn ($q) => $q->whereNull('nip')->orWhere('nip', ''))
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                    ->first();
            }

            try {
                $this->persist($teacher, [
                    'name' => $name,
                    'nip' => $nip !== '' ? $nip : null,
                    'tmt' => $tmt,
                    'is_active' => $isActive,
                ], $this->cleanSubjects($split($cell($row, 'subjects'))), $allPrograms, array_values(array_unique($concIds)), $keys, $classes);

                $teacher ? $updated++ : $created++;
            } catch (ValidationException $e) {
                $skipped++;
                $issues[] = "Baris {$line} dilewati: ".collect($e->errors())->flatten()->first();
            }
        }

        return back()
            ->with('success', "Import selesai: {$created} data baru, {$updated} diperbarui, {$skipped} dilewati.")
            ->with('import_issues', array_slice($issues, 0, 30));
    }

    /* ------------------------------------------------------------------ */
    /*  Internal                                                           */
    /* ------------------------------------------------------------------ */

    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'tmt' => ['nullable', 'date'],
            'subjects' => ['nullable', 'array', 'max:10'],
            'subjects.*' => ['string', 'max:60'],
            'concentrations' => ['nullable', 'array'],
            'concentrations.*' => ['integer', 'exists:concentrations,id'],
            'teaches_all_programs' => ['nullable', 'boolean'],
            'positions' => ['nullable', 'array'],
            'positions.*' => ['string', Rule::in(array_keys(config('struktur.positions')))],
            'position_classes' => ['nullable', 'array'],
            'position_classes.*' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Simpan guru beserta jabatan, konsentrasi, dan mata pelajarannya.
     *
     * @param  array<int,string>|null  $keys  kunci jabatan; null = jangan ubah jabatan yang ada
     * @param  array<string,string>  $classes  kelas per kunci jabatan (wali kelas)
     *
     * @throws ValidationException bila jabatan tunggal sudah dipegang orang lain
     */
    private function persist(?Teacher $teacher, array $attrs, array $subjects, bool $allPrograms, array $concIds, ?array $keys, array $classes): Teacher
    {
        $defs = config('struktur.positions');

        if ($keys !== null) {
            $keys = array_values(array_unique($keys));

            foreach ($keys as $key) {
                if (($defs[$key]['single'] ?? false) !== true) {
                    continue;
                }

                $holder = TeacherPosition::with('teacher:id,name')
                    ->where('position_key', $key)
                    ->when($teacher, fn ($q) => $q->where('teacher_id', '!=', $teacher->id))
                    ->first();

                if ($holder) {
                    throw ValidationException::withMessages([
                        'positions' => '"'.$defs[$key]['label'].'" sudah dipegang '.($holder->teacher->name ?? 'guru lain').'. Kosongkan dulu jabatan itu di data yang bersangkutan.',
                    ]);
                }
            }
        }

        $effectiveKeys = $keys ?? ($teacher ? $teacher->positions->pluck('position_key')->all() : []);

        // Kepala sekolah dan tata usaha tidak mengajar
        $nonTeaching = collect($effectiveKeys)->contains(fn ($k) => ($defs[$k]['teaches'] ?? true) === false);

        if ($nonTeaching) {
            $subjects = [];
            $allPrograms = false;
            $concIds = [];
        } elseif ($allPrograms) {
            $concIds = [];
        }

        $labels = collect($effectiveKeys)->map(fn ($k) => $defs[$k]['label'] ?? $k)->implode(', ');

        $attrs += [
            'position' => $labels !== '' ? Str::limit($labels, 250, '') : 'Guru',
            'subjects' => $subjects ?: null,
            'subject' => $subjects ? Str::limit(implode('; ', $subjects), 250, '') : null, // kolom lama, tetap diisi
            'teaches_all_programs' => $allPrograms,
            'program_id' => $concIds ? Concentration::whereKey($concIds[0])->value('program_id') : null,
            'is_management' => $effectiveKeys !== [],
        ];

        return DB::transaction(function () use ($teacher, $attrs, $concIds, $keys, $classes, $defs) {
            if ($teacher) {
                $teacher->update($attrs);
            } else {
                $teacher = Teacher::create($attrs);
            }

            $teacher->concentrations()->sync($concIds);

            if ($keys !== null) {
                $teacher->positions()->whereNotIn('position_key', $keys)->delete();

                foreach ($keys as $key) {
                    $teacher->positions()->updateOrCreate(
                        ['position_key' => $key],
                        ['class_label' => ($defs[$key]['class'] ?? false) && filled($classes[$key] ?? null) ? trim($classes[$key]) : null]
                    );
                }
            }

            return $teacher;
        });
    }

    /** Rapikan daftar mata pelajaran: buang kosong/duplikat, maksimal 10, tiap item maksimal 60 karakter. */
    private function cleanSubjects(array $subjects): array
    {
        $seen = [];
        $clean = [];

        foreach ($subjects as $subject) {
            $subject = Str::limit(trim((string) $subject), 60, '');
            $lower = mb_strtolower($subject);

            if ($subject === '' || isset($seen[$lower])) {
                continue;
            }

            $seen[$lower] = true;
            $clean[] = $subject;
        }

        return array_slice($clean, 0, 10);
    }

    private function norm(string $text): string
    {
        return preg_replace('/[^a-z0-9]/', '', mb_strtolower($text));
    }

    /** Terima tanggal teks (2010-01-01, 1/1/2010) maupun nomor seri Excel. */
    private function parseDate(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        if (ctype_digit($value) && (int) $value > 20000 && (int) $value < 80000) {
            return Carbon::create(1899, 12, 30)->addDays((int) $value)->toDateString();
        }

        foreach (['Y-m-d', 'j/n/Y', 'j-n-Y', 'j/n/y'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
            } catch (Throwable $e) {
                continue;
            }

            if ($date) {
                return $date->toDateString();
            }
        }

        return null;
    }
}
