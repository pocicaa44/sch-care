<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'location' => 'required|string|max:1000',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // 'images' => 'nullable|array|max:5',
        ];
    }
}
