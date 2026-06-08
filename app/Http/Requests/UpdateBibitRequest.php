<?php

namespace App\Http\Requests;

use App\Models\Bibit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBibitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('input-bibit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $bibit = $this->route('bibit');
        $bibitId = $bibit instanceof \App\Models\Bibit ? $bibit->id : $bibit;

        return [
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'kandang_id' => [
                'required',
                'exists:kandangs,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($bibitId) {
                    $status = $this->input('status', 'aktif');
                    if ($status !== 'aktif') {
                        return;
                    }

                    $hasOtherActiveBibit = Bibit::query()
                        ->where('kandang_id', $value)
                        ->where('status', 'aktif')
                        ->where('id', '!=', $bibitId)
                        ->exists();

                    if ($hasOtherActiveBibit) {
                        $fail('Kandang ini masih memiliki bibit status Aktif. Ubah status bibit sebelumnya terlebih dahulu.');
                    }
                },
            ],
            'jenis_bibit' => ['required', 'string', 'max:255'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(['aktif', 'non-aktif', 'sudah selesai'])],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status tidak valid.',
        ];
    }
}
