<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * - teachers.subjects: mata pelajaran bisa lebih dari satu (JSON)
     * - teachers.teaches_all_programs: mengajar mapel umum di semua program
     * - concentration_teacher: guru mengajar di konsentrasi mana saja (TKJ, TKR, ...)
     * - teacher_positions: jabatan struktural (kunci jabatan ada di config/struktur.php);
     *   satu orang boleh punya beberapa jabatan
     */
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->json('subjects')->nullable()->after('subject');
            $table->boolean('teaches_all_programs')->default(false)->after('subjects');
        });

        Schema::create('concentration_teacher', function (Blueprint $table) {
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('concentration_id')->constrained()->cascadeOnDelete();
            $table->primary(['teacher_id', 'concentration_id']);
        });

        Schema::create('teacher_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->string('position_key', 60);
            $table->string('class_label', 50)->nullable(); // mis. "XI TKJ 1" untuk wali kelas
            $table->timestamps();

            $table->unique(['teacher_id', 'position_key']);
            $table->index('position_key');
        });

        $this->migrateExistingData();
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_positions');
        Schema::dropIfExists('concentration_teacher');

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['subjects', 'teaches_all_programs']);
        });
    }

    /**
     * Data lama tidak hilang:
     *  - mata pelajaran (teks tunggal) -> daftar berisi satu mata pelajaran
     *  - program keahlian yang hanya punya satu konsentrasi -> konsentrasi itu
     *  - jabatan teks bebas yang persis sama dengan label di config -> jabatan struktural
     */
    private function migrateExistingData(): void
    {
        $norm = fn (?string $s) => preg_replace('/[^a-z0-9]/', '', mb_strtolower((string) $s));

        $labelToKey = [];
        foreach (config('struktur.positions', []) as $key => $def) {
            $labelToKey[$norm($def['label'])] = $key;
        }

        $programConcentrations = DB::table('concentrations')->get()->groupBy('program_id');
        $now = now();

        foreach (DB::table('teachers')->get() as $t) {
            if (filled($t->subject)) {
                DB::table('teachers')->where('id', $t->id)->update([
                    'subjects' => json_encode([trim($t->subject)], JSON_UNESCAPED_UNICODE),
                ]);
            }

            if ($t->program_id && ($list = $programConcentrations->get($t->program_id)) && $list->count() === 1) {
                DB::table('concentration_teacher')->insertOrIgnore([
                    'teacher_id' => $t->id,
                    'concentration_id' => $list->first()->id,
                ]);
            }

            if ($t->is_management && isset($labelToKey[$norm($t->position)])) {
                DB::table('teacher_positions')->insertOrIgnore([
                    'teacher_id' => $t->id,
                    'position_key' => $labelToKey[$norm($t->position)],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
};
