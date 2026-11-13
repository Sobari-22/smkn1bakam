<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concentration;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramAdminController extends Controller
{
    public function index(): View
    {
        $programs = Program::with('concentrations')->get();

        return view('admin.program.index', compact('programs'));
    }

    // ---------- Program keahlian (jurusan) ----------

    public function createProgram(): View
    {
        return view('admin.program.program-form', ['program' => new Program()]);
    }

    public function storeProgram(Request $request): RedirectResponse
    {
        Program::create($this->programData($request));

        return redirect()->route('admin.program.index')->with('success', 'Program keahlian berhasil ditambahkan.');
    }

    public function editProgram(Program $program): View
    {
        return view('admin.program.program-form', compact('program'));
    }

    public function updateProgram(Request $request, Program $program): RedirectResponse
    {
        $program->update($this->programData($request, $program));

        return redirect()->route('admin.program.index')->with('success', 'Program keahlian berhasil disimpan.');
    }

    // ---------- Konsentrasi ----------

    public function createConcentration(Program $program): View
    {
        return view('admin.program.concentration-form', [
            'program' => $program,
            'concentration' => new Concentration(),
        ]);
    }

    public function storeConcentration(Request $request, Program $program): RedirectResponse
    {
        $data = $this->concentrationData($request);
        $data['program_id'] = $program->id;

        $concentration = Concentration::create($data);

        return redirect()
            ->route('admin.program.concentrations.edit', $concentration)
            ->with('success', 'Konsentrasi berhasil ditambahkan.');
    }

    public function editConcentration(Concentration $concentration): View
    {
        $program = Program::findOrFail($concentration->program_id);

        return view('admin.program.concentration-form', compact('concentration', 'program'));
    }

    public function updateConcentration(Request $request, Concentration $concentration): RedirectResponse
    {
        $concentration->update($this->concentrationData($request, $concentration));

        return back()->with('success', 'Data konsentrasi berhasil disimpan.');
    }

    public function updateQuota(Request $request, Concentration $concentration): RedirectResponse
    {
        $data = $request->validate([
            'quota' => ['required', 'integer', 'min:0', 'max:9999'],
        ], [], ['quota' => 'kuota']);

        $concentration->update($data);

        return back()->with('success', "Kuota {$concentration->name} diperbarui menjadi {$data['quota']}.");
    }

    // ---------- Helper ----------

    private function programData(Request $request, ?Program $program = null): array
    {
        // Slug kosong dibuat otomatis dari nama.
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('name', ''))]);

        return $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'slug'          => ['required', 'string', 'max:255', Rule::unique('programs', 'slug')->ignore($program?->id)],
            'accreditation' => ['nullable', 'string', 'max:10'],
            'description'   => ['nullable', 'string'],
        ], [], [
            'name'          => 'nama program',
            'slug'          => 'slug',
            'accreditation' => 'akreditasi',
            'description'   => 'deskripsi',
        ]);
    }

    private function concentrationData(Request $request, ?Concentration $concentration = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('name', ''))]);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'title'       => ['nullable', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', Rule::unique('concentrations', 'slug')->ignore($concentration?->id)],
            'description' => ['nullable', 'string'],
            'overview'    => ['nullable', 'string'],
            'quota'       => ['required', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ], [], [
            'name'        => 'singkatan konsentrasi',
            'title'       => 'nama lengkap',
            'slug'        => 'slug',
            'description' => 'deskripsi singkat',
            'overview'    => 'penjelasan lengkap',
            'quota'       => 'kuota',
            'image'       => 'foto',
        ]);

        // Kolom daftar: satu item per baris di textarea -> array (cast JSON di model).
        foreach (['competencies', 'career_prospects', 'advantages', 'further_studies'] as $field) {
            $data[$field] = $this->linesToArray($request->input($field));
        }

        // Foto: ganti kalau ada upload baru, hapus kalau dicentang.
        if ($request->hasFile('image')) {
            if ($concentration?->image) {
                Storage::disk('public')->delete($concentration->image);
            }
            $data['image'] = $request->file('image')->store('concentrations', 'public');
        } else {
            unset($data['image']);
            if ($concentration?->image && $request->boolean('remove_image')) {
                Storage::disk('public')->delete($concentration->image);
                $data['image'] = null;
            }
        }

        return $data;
    }

    private function linesToArray(?string $value): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $value ?? '');

        return array_values(array_filter(array_map('trim', $lines), fn ($line) => $line !== ''));
    }
}