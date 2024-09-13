<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name"          => "required|max:255",
            "slug"          => "required|max:5|min:3",
            "description"   => "required",
            "price"         => "required|numeric",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required"         => "Nama tidak boleh kosong",
            "name.max"              => "Batas maksimal nama adalah 255 karakter",
            "slug.required"         => "Slug tidak boleh kosong",
            "slug.min"              => "Slug minimal 3 karakter",
            "slug.max"              => "Batas maksimal slug adalah 5 karakter",
            "description.required"  => "Deskripsi tidak boleh kosong",
            "price.required"        => "Harga tidak boleh kosong",
            "price.numeric"         => "Harga hanya boleh diisi angka",
        ];
    }
}
