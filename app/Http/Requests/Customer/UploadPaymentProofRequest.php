<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UploadPaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // mimes dicek dari isi file (bukan cuma ekstensi), max dalam KB.
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'proof.mimes' => 'Format file harus jpg, jpeg, png, atau webp.',
            'proof.max' => 'Ukuran file maksimal 2MB.',
        ];
    }
}
