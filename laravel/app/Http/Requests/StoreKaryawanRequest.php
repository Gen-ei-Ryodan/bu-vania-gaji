<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKaryawanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage-master-data');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255', 'unique:karyawans,nama'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'status_aktif' => ['sometimes', 'boolean'],
            'gaji_pokok' => ['required', 'numeric', 'min:0'],
            'berlaku_mulai' => ['required', 'date'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
