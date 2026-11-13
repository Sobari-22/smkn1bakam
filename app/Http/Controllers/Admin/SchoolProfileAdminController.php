<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Kelola konten halaman Profil Sekolah (Sejarah, Visi & Misi, Sambutan Kepala
 * Sekolah) yang disimpan di tabel `pages` dengan slug tetap. Hanya superadmin
 * (permission `profil` tidak diberikan ke role lain di config/roles.php).
 *
 * Bila sebuah bagian dihapus, halaman publik otomatis kembali menampilkan
 * teks bawaan yang tertulis di resources/views/profil.blade.php.
 */
class SchoolProfileAdminController extends Controller
{
    public const SECTIONS = [
        'sejarah' => 'Sejarah Sekolah',
        'visi-misi' => 'Visi & Misi',
        'sambutan-kepala' => 'Sambutan Kepala Sekolah',
    ];

    /** Teks bawaan, hanya dipakai untuk mengisi awal formulir bila belum ada data. */
    private const DEFAULTS = [
        'sejarah' => [
            'title' => 'Perjalanan Panjang Sejak 2008',
            'content' => "SMKN 1 Bakam didirikan pada tahun 2008 sebagai respons atas kebutuhan pendidikan vokasi di Kecamatan Bakam, Kabupaten Bangka Barat, Kepulauan Bangka Belitung. Sekolah ini hadir untuk memberikan akses pendidikan kejuruan berkualitas bagi putra-putri daerah tanpa harus merantau jauh.\n\nDimulai dengan dua Program Keahlian dan 80 siswa perdana, SMKN 1 Bakam terus berkembang hingga kini memiliki 2 Program Keahlian dengan 3 konsentrasi, 450+ siswa aktif, dan puluhan tenaga pendidik berpengalaman.\n\nSekolah ini berkomitmen menjadi pusat keunggulan vokasi di Bangka Barat, menghubungkan dunia pendidikan dengan dunia industri demi menciptakan lulusan yang langsung siap kerja.",
        ],
        'visi-misi' => [
            'excerpt' => 'Menjadi Sekolah Menengah Kejuruan unggulan yang menghasilkan lulusan kompeten, beriman, berakhlak mulia, berwawasan lingkungan, dan mampu bersaing di dunia kerja maupun berwirausaha.',
            'content' => "Menyelenggarakan pembelajaran vokasi yang berkualitas, inovatif, dan relevan dengan kebutuhan industri.\nMembentuk karakter siswa yang beriman, disiplin, jujur, dan bertanggung jawab.\nMembangun kemitraan strategis dengan dunia usaha dan dunia industri (DUDI) untuk peningkatan kompetensi.\nMenyiapkan lulusan yang kompeten, berdaya saing, dan siap memasuki pasar kerja nasional maupun internasional.\nMengembangkan jiwa wirausaha berbasis potensi lokal Bangka Belitung.\nMewujudkan sekolah yang berwawasan lingkungan dan berbudaya mutu.",
        ],
        'sambutan-kepala' => [
            'title' => '',
            'content' => "Puji syukur kehadirat Allah SWT atas segala limpahan rahmat dan hidayah-Nya sehingga SMKN 1 Bakam terus tumbuh dan berkembang menjadi lembaga pendidikan vokasi yang semakin dipercaya oleh masyarakat Bangka Barat.\n\nPendidikan vokasi adalah tulang punggung kemajuan industri bangsa. Di SMKN 1 Bakam, kami tidak hanya mengajarkan keterampilan teknis, tetapi juga membentuk karakter — kejujuran, kedisiplinan, dan semangat pantang menyerah — yang merupakan bekal utama dalam kehidupan nyata.\n\nKami mengundang putra-putri terbaik Bangka Barat untuk bergabung bersama kami. Bersama, kita wujudkan generasi vokasi yang unggul, berkarakter, dan siap membangun daerah serta bangsa tercinta.\n\nWassalamu'alaikum Wr. Wb.",
        ],
    ];

    public function index(): View
    {
        $pages = Page::whereIn('slug', array_keys(self::SECTIONS))->get()->keyBy('slug');

        $forms = [];

        foreach (self::SECTIONS as $slug => $label) {
            $page = $pages->get($slug);
            $defaults = self::DEFAULTS[$slug];

            $forms[$slug] = [
                'label' => $label,
                'page' => $page,
                'exists' => $page !== null,
                'title' => $page ? (string) $page->title : ($defaults['title'] ?? ''),
                'excerpt' => $page ? (string) $page->excerpt : ($defaults['excerpt'] ?? ''),
                'content' => $page
                    ? ($slug === 'visi-misi' ? (string) $page->content : $this->toPlainText($page->content))
                    : ($defaults['content'] ?? ''),
            ];
        }

        return view('admin.school-profile.index', compact('forms'));
    }

    public function update(Request $request, string $section): RedirectResponse
    {
        abort_unless(array_key_exists($section, self::SECTIONS), 404);

        $rules = [
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ];

        if ($section === 'visi-misi') {
            // kolom `excerpt` bertipe string (maks. 255 karakter)
            $rules['excerpt'] = ['required', 'string', 'max:255'];
            $rules['content'] = ['nullable', 'string', 'max:5000'];
        } else {
            $rules['title'] = ['required', 'string', 'max:255'];
            $rules['content'] = ['required', 'string', 'max:30000'];
        }

        $data = $request->validateWithBag($section, $rules, [], [
            'title' => $section === 'sambutan-kepala' ? 'nama kepala sekolah' : 'judul',
            'excerpt' => 'visi',
            'content' => $section === 'visi-misi' ? 'misi' : 'isi',
            'image' => 'gambar',
        ]);

        $page = Page::firstOrNew(['slug' => $section]);

        if ($section === 'visi-misi') {
            $page->title = 'Visi dan Misi';
            $page->excerpt = trim($data['excerpt']);
            $page->content = $this->normalizeLines($data['content'] ?? '');
        } else {
            $page->title = trim($data['title']);
            $page->content = $this->toHtml($data['content']);
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($page);
            $page->image = $request->file('image')->store('pages', 'public');
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImage($page);
            $page->image = null;
        }

        $page->save();

        return redirect()
            ->to(route('admin.school-profile.index').'#'.$section)
            ->with('success', self::SECTIONS[$section].' berhasil disimpan.');
    }

    public function destroy(string $section): RedirectResponse
    {
        abort_unless(array_key_exists($section, self::SECTIONS), 404);

        $page = Page::where('slug', $section)->first();

        if ($page) {
            $this->deleteImage($page);
            $page->delete();
        }

        return redirect()
            ->to(route('admin.school-profile.index').'#'.$section)
            ->with('success', self::SECTIONS[$section].' dihapus. Halaman publik kembali menampilkan teks bawaan.');
    }

    private function deleteImage(Page $page): void
    {
        if (! empty($page->image) && ! str_starts_with($page->image, 'http')) {
            Storage::disk('public')->delete($page->image);
        }
    }

    /**
     * Teks biasa (paragraf dipisah baris kosong) -> HTML aman. Semua karakter
     * di-escape dulu, jadi tidak ada HTML/script dari input yang bisa lolos
     * ke halaman publik (yang menampilkannya dengan {!! !!}).
     */
    private function toHtml(string $text): string
    {
        $paragraphs = preg_split('/\R{2,}/', trim($text)) ?: [];

        return collect($paragraphs)
            ->map(fn ($p) => trim($p))
            ->filter()
            ->map(fn ($p) => '<p>'.nl2br(e($p), false).'</p>')
            ->implode("\n");
    }

    /** Kebalikan toHtml(), supaya konten lama (HTML) tetap bisa diedit sebagai teks biasa. */
    private function toPlainText(?string $html): string
    {
        $text = preg_replace('#</p>\s*<p[^>]*>#i', "\n\n", (string) $html);
        $text = preg_replace('#<br\s*/?>#i', "\n", (string) $text);

        return trim(html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    /** Daftar misi: satu butir per baris, baris kosong dibuang. */
    private function normalizeLines(string $text): string
    {
        return collect(preg_split('/\R/', $text) ?: [])
            ->map(fn ($l) => trim($l))
            ->filter()
            ->implode("\n");
    }
}
