<?php

namespace App\Http\Controllers;

use App\Models\Concentration;
use App\Models\PpdbRegistrant;
use App\Models\Program;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class PpdbController extends Controller
{
    /**
     * Single page with two Alpine tabs ("Formulir Pendaftaran" and
     * "Cek Status Pendaftaran"), matching the design — no separate page
     * reload between them.
     *
     * When SPMB is closed (Setting::get('ppdb_status') !== 'open'), the
     * "Formulir Pendaftaran" tab shows a closed notice instead of the
     * stepper form — but "Cek Status Pendaftaran" stays fully working,
     * since people who already registered still need it.
     */
    public function create(Request $request): View
    {
        $ppdbOpen = $this->isPpdbOpen();

        $programs = Program::with('concentrations')->get();

        // Pre-select program/concentration when arriving from the
        // "Daftar ke Konsentrasi Ini" button on the Program Keahlian page.
        $selectedProgramId = $request->integer('program') ?: null;
        $selectedConcentrationId = $request->integer('concentration') ?: null;

        return view('ppdb.index', [
            'programs' => $programs,
            'selectedProgramId' => $selectedProgramId,
            'selectedConcentrationId' => $selectedConcentrationId,
            'ppdbOpen' => $ppdbOpen,
            'closedMessage' => Setting::get(
                'ppdb_closed_message',
                'Pendaftaran SPMB saat ini belum dibuka. Silakan pantau pengumuman resmi sekolah atau media sosial kami untuk info jadwal pembukaan.'
            ),
            // Tanggal pendaftaran dibuka (diisi admin saat SPMB ditutup), format Indonesia.
            'openDate' => $this->openDateLabel(),
            'schedule' => Setting::spmbSchedule(),
            // Opens the status tab by default if the form tab isn't usable.
            'defaultTab' => $ppdbOpen ? 'form' : 'status',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Guarded server-side too — the form tab is hidden when closed, but
        // someone could still have an old tab open from before an admin
        // closed registration.
        if (! $this->isPpdbOpen()) {
            return back()->withErrors([
                'ppdb' => 'Mohon maaf, pendaftaran SPMB baru saja ditutup oleh admin.',
            ]);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P'],
            'nik' => ['required', 'digits:16'],
            'nisn' => ['required', 'digits_between:8,10'],
            'no_kk' => ['required', 'digits:16'],
            'address' => ['required', 'string'],
            'program_id' => ['required', 'exists:programs,id'],
            'concentration_id' => ['nullable', 'exists:concentrations,id'],
            'has_kip' => ['nullable', 'boolean'],
            'has_kis' => ['nullable', 'boolean'],
            'has_pkh' => ['nullable', 'boolean'],
            'no_aid' => ['nullable', 'boolean'],
            // TODO: add 'g-recaptcha-response' => ['required'] once reCAPTCHA is wired up
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin tidak valid.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus tepat 16 digit angka (tanpa spasi atau tanda baca).',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits_between' => 'NISN harus 8–10 digit angka (tanpa spasi atau tanda baca).',
            'no_kk.required' => 'Nomor Kartu Keluarga wajib diisi.',
            'no_kk.digits' => 'Nomor Kartu Keluarga harus tepat 16 digit angka (tanpa spasi atau tanda baca).',
            'address.required' => 'Domisili/alamat wajib diisi.',
            'program_id.required' => 'Program keahlian wajib dipilih.',
            'program_id.exists' => 'Program keahlian yang dipilih tidak valid.',
            'concentration_id.exists' => 'Konsentrasi yang dipilih tidak valid.',
        ]);

        // Wajib memilih salah satu: minimal satu kartu, atau "Tidak ada".
        if (! $request->boolean('has_kip') && ! $request->boolean('has_kis')
            && ! $request->boolean('has_pkh') && ! $request->boolean('no_aid')) {
            return back()->withInput()->withErrors([
                'ppdb' => 'Pilih salah satu kartu bantuan sosial yang dimiliki, atau pilih "Tidak ada".',
            ]);
        }

        $registrant = PpdbRegistrant::create([
            ...collect($validated)->except('no_aid')->all(),
            'has_kip' => $request->boolean('has_kip'),
            'has_kis' => $request->boolean('has_kis'),
            'has_pkh' => $request->boolean('has_pkh'),
            'registration_number' => PpdbRegistrant::generateRegistrationNumber(),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('ppdb.success', $registrant->registration_number);
    }

    public function success(string $registrationNumber): View
    {
        $registrant = PpdbRegistrant::with(['program', 'concentration'])
            ->where('registration_number', $registrationNumber)
            ->firstOrFail();

        return view('ppdb.success', compact('registrant'));
    }

    /**
     * AJAX endpoint backing the "Cek Status Pendaftaran" tab — returns the
     * registrant's status without a page reload. Bound to route
     * ppdb.check-status.check (POST /ppdb/cek-status) — see routes/web.php.
     * Stays available even when SPMB registration is closed.
     */
    public function checkStatusApi(Request $request): JsonResponse
    {
        $request->validate(['registration_number' => ['required', 'string']]);

        $registrant = PpdbRegistrant::with(['program', 'concentration'])
            ->where('registration_number', $request->registration_number)
            ->first();

        if (! $registrant) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'registration_number' => $registrant->registration_number,
            'full_name' => $registrant->full_name,
            'program' => $registrant->program?->name,
            'concentration' => $registrant->concentration?->name,
            'status' => $registrant->status,
            'created_at' => $registrant->created_at->translatedFormat('d F Y'),
        ]);
    }

    /**
     * AJAX endpoint used by the program -> concentration dependent dropdown
     * on the registration form.
     */
    public function concentrationsByProgram(Program $program)
    {
        return response()->json(
            $program->concentrations()->select('id', 'name', 'title')->get()
        );
    }

    private function openDateLabel(): ?string
    {
        $date = Setting::get('ppdb_open_date');

        if (blank($date)) {
            return null;
        }

        try {
            return Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y');
        } catch (\Throwable) {
            return null;
        }
    }

    private function isPpdbOpen(): bool
    {
        return Setting::get('ppdb_status', 'open') === 'open';
    }
}