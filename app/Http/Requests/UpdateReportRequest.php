<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization dilakukan di controller via policy
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'location' => 'required|string|max:1000',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'exists:report_images,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul wajib diisi',
            'location.required' => 'Lokasi wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.max' => 'Ukuran gambar maksimal 2MB',
            'deleted_images.*.exists' => 'Gambar yang dipilih tidak valid',
        ];
    }
}