<?php

namespace App\Http\Requests;

use App\Enums\KategoriKeripik;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKeripikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', Rule::in(KategoriKeripik::values())],
            'harga' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'berat' => ['required', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'gambar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'gambar_url' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama keripik wajib diisi.',
            'kategori.required' => 'Kategori produk wajib dipilih.',
            'kategori.in' => 'Pilih salah satu kategori produk yang tersedia.',
            'harga.required' => 'Harga produk wajib diisi.',
            'harga.integer' => 'Harga harus berupa angka bulat positif.',
            'stok.required' => 'Jumlah stok wajib diisi.',
            'berat.required' => 'Berat kemasan produk wajib diisi.',
            'gambar_file.image' => 'File yang diunggah harus berupa gambar (JPG, PNG, WEBP, GIF).',
            'gambar_file.max' => 'Ukuran file gambar maksimal 5 MB.',
            'gambar_url.url' => 'Format URL gambar tidak valid.',
        ];
    }
}
