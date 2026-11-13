<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Kelola Sarana & Prasarana yang tampil di tab "Sarana & Prasarana" pada
 * halaman Profil. Hanya superadmin (permission `profil`).
 */
class FacilityAdminController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::ordered()->get();

        return view('admin.facilities.index', compact('facilities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('new', $this->rules(), [], $this->attributes());

        $facility = new Facility($this->fields($data));
        $facility->order = $data['order'] ?? ((int) Facility::max('order') + 1);

        if ($request->hasFile('image')) {
            $facility->image = $request->file('image')->store('facilities', 'public');
        }

        $facility->save();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas "'.$facility->name.'" berhasil ditambahkan.');
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $data = $request->validateWithBag('facility-'.$facility->id, $this->rules(), [], $this->attributes());

        $facility->fill($this->fields($data));
        $facility->order = $data['order'] ?? $facility->order;

        if ($request->hasFile('image')) {
            $this->deleteImage($facility);
            $facility->image = $request->file('image')->store('facilities', 'public');
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImage($facility);
            $facility->image = null;
        }

        $facility->save();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas "'.$facility->name.'" berhasil diperbarui.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        $name = $facility->name;

        $this->deleteImage($facility);
        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas "'.$name.'" berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'icon' => ['nullable', 'string', 'max:16'],
            'name' => ['required', 'string', 'max:255'],
            'qty' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }

    private function attributes(): array
    {
        return [
            'icon' => 'ikon',
            'name' => 'nama fasilitas',
            'qty' => 'jumlah',
            'description' => 'deskripsi',
            'order' => 'urutan',
            'image' => 'gambar',
        ];
    }

    /** Field teks saja; gambar dan urutan ditangani terpisah. */
    private function fields(array $data): array
    {
        return [
            'icon' => filled($data['icon'] ?? null) ? trim($data['icon']) : null,
            'name' => trim($data['name']),
            'qty' => filled($data['qty'] ?? null) ? trim($data['qty']) : null,
            'description' => filled($data['description'] ?? null) ? trim($data['description']) : null,
        ];
    }

    private function deleteImage(Facility $facility): void
    {
        if (! empty($facility->image) && ! str_starts_with($facility->image, 'http')) {
            Storage::disk('public')->delete($facility->image);
        }
    }
}
