<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Keripik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(): View
    {
        return view('admin.kategori.index', [
            'kategoris' => Kategori::query()->withCount('keripiks')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('kategoris', 'nama')],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max' => 'Nama kategori maksimal 255 karakter.',
            'nama.unique' => 'Nama kategori tersebut sudah ada.',
        ]);

        Kategori::create($data);

        return to_route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori): View
    {
        return view('admin.kategori.edit', ['kategori' => $kategori]);
    }

    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $data = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategoris', 'nama')->ignore($kategori->id),
            ],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max' => 'Nama kategori maksimal 255 karakter.',
            'nama.unique' => 'Nama kategori tersebut sudah ada.',
        ]);

        $namaLama = $kategori->nama;

        DB::transaction(function () use ($data, $kategori, $namaLama): void {
            $kategori->update($data);

            Keripik::where('kategori', $namaLama)->update(['kategori' => $data['nama']]);
        });

        return to_route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        if ($kategori->keripiks()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh produk. Pindahkan produk ke kategori lain terlebih dahulu.');
        }

        $kategori->delete();

        return to_route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
