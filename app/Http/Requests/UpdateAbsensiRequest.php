<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAbsensiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('input-absensi');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'karyawan_id' => ['required', 'exists:karyawans,id'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'kandang_id' => ['required', 'exists:kandangs,id'],
            'bibit_id' => ['required', 'exists:bibits,id'],
            'tipe_absen' => ['required', 'in:full,half'],
            'tanggal' => ['required', 'date'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $karyawanId = $this->input('karyawan_id');
            $tanggal = $this->input('tanggal');
            $tipeAbsen = $this->input('tipe_absen');
            $currentAbsensiId = $this->route('absensi')->id; // Get current absensi ID being updated
            
            // Check for existing full day records for the same karyawan and date
            // Allow multiple half day records but prevent duplicate full day records
            if ($tipeAbsen === 'full') {
                $existingFullDay = \App\Models\Absensi::where('karyawan_id', $karyawanId)
                    ->where('tanggal', $tanggal)
                    ->where('tipe_absen', 'full')
                    ->where('id', '!=', $currentAbsensiId) // Exclude current record being updated
                    ->exists();
                    
                if ($existingFullDay) {
                    $karyawanModel = \App\Models\Karyawan::find($karyawanId);
                    $karyawanNama = $karyawanModel ? $karyawanModel->nama : 'Karyawan';
                    $validator->errors()->add('tipe_absen', "{$karyawanNama} sudah memiliki absensi Full Day pada tanggal " . date('d/m/Y', strtotime($tanggal)) . ".");
                }
            }

            if ($tipeAbsen === 'half') {
                $existingHalfDayCount = \App\Models\Absensi::where('karyawan_id', $karyawanId)
                    ->where('tanggal', $tanggal)
                    ->where('tipe_absen', 'half')
                    ->where('id', '!=', $currentAbsensiId)
                    ->count();

                if ($existingHalfDayCount >= 2) {
                    $karyawanModel = \App\Models\Karyawan::find($karyawanId);
                    $karyawanNama = $karyawanModel ? $karyawanModel->nama : 'Karyawan';
                    $validator->errors()->add('tipe_absen', "{$karyawanNama} sudah mencapai batas 2x Half Day pada tanggal " . date('d/m/Y', strtotime($tanggal)) . ".");
                }
            }
        });
    }
}
