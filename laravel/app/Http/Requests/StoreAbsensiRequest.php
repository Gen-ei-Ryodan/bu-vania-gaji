<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsensiRequest extends FormRequest
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
            'karyawan' => ['required', 'array', 'min:1'],
            'karyawan.*' => ['required', 'exists:karyawans,id'],
            'jabatan' => ['required', 'array'],
            'jabatan.*' => ['required', 'exists:jabatans,id'],
            // 'lokasi_id' => ['required', 'exists:lokasis,id'], // Auto-filled from bibit
            // 'kandang_id' => ['required', 'exists:kandangs,id'], // Auto-filled from bibit
            'bibit_id' => ['required', 'exists:bibits,id'],
            'tipe_absen' => ['required', 'array'],
            'tipe_absen.*' => ['required', 'in:full,half'],
            'tanggal' => ['required', 'date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'karyawan.required' => 'Minimal harus memilih satu karyawan.',
            'karyawan.min' => 'Minimal harus memilih satu karyawan.',
            'karyawan.*.required' => 'Karyawan harus dipilih.',
            'karyawan.*.exists' => 'Karyawan yang dipilih tidak valid.',
            'jabatan.*.required' => 'Jabatan harus diisi.',
            'jabatan.*.exists' => 'Jabatan yang dipilih tidak valid.',
            'tipe_absen.*.required' => 'Tipe absensi harus dipilih.',
            'tipe_absen.*.in' => 'Tipe absensi harus Full Day atau Half Day.',
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
            $karyawan = $this->input('karyawan', []);
            $tanggal = $this->input('tanggal');
            $tipeAbsens = $this->input('tipe_absen', []);
            
            // Check for duplicate karyawan in the same request
            if (count($karyawan) !== count(array_unique($karyawan))) {
                $validator->errors()->add('karyawan', 'Tidak boleh memilih karyawan yang sama lebih dari sekali.');
            }
            
            // Check for existing full day records for the same karyawan and date
            // Allow multiple half day records but prevent duplicate full day records
            foreach ($karyawan as $index => $karyawanId) {
                $tipeAbsen = $tipeAbsens[$index] ?? 'full';
                
                if ($tipeAbsen === 'full') {
                    $existingFullDay = \App\Models\Absensi::where('karyawan_id', $karyawanId)
                        ->where('tanggal', $tanggal)
                        ->where('tipe_absen', 'full')
                        ->exists();
                        
                    if ($existingFullDay) {
                        $karyawanModel = \App\Models\Karyawan::find($karyawanId);
                        $karyawanNama = $karyawanModel ? $karyawanModel->nama : 'Karyawan';
                        $validator->errors()->add('karyawan.' . $index, "{$karyawanNama} sudah memiliki absensi Full Day pada tanggal " . date('d/m/Y', strtotime($tanggal)) . ".");
                    }
                }

                if ($tipeAbsen === 'half') {
                    $existingHalfDayCount = \App\Models\Absensi::where('karyawan_id', $karyawanId)
                        ->where('tanggal', $tanggal)
                        ->where('tipe_absen', 'half')
                        ->count();

                    if ($existingHalfDayCount >= 2) {
                        $karyawanModel = \App\Models\Karyawan::find($karyawanId);
                        $karyawanNama = $karyawanModel ? $karyawanModel->nama : 'Karyawan';
                        $validator->errors()->add('karyawan.' . $index, "{$karyawanNama} sudah mencapai batas 2x Half Day pada tanggal " . date('d/m/Y', strtotime($tanggal)) . ".");
                    }
                }
            }
        });
    }
}
