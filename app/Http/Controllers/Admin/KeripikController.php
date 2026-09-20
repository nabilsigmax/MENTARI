<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKeripikRequest;
use App\Http\Requests\UpdateKeripikRequest;
use App\KategoriKeripik;
use App\Models\Keripik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KeripikController extends Controller
{
    public function index(Request $request): View
    {
        $query = Keripik::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategori = $request->input('kategori')) {
            $query->where('kategori', $kategori);
        }

        if ($request->has('status') && $request->input('status') !== null && $request->input('status') !== '') {
            $query->where('is_active', (bool) $request->input('status'));
        }

        $categories = Keripik::select('kategori')->distinct()->pluck('kategori');

        return view('admin.keripik.index', [
            'keripiks' => $query->latest()->paginate(10)->withQueryString(),
            'totalKeripik' => Keripik::count(),
            'keripikAktif' => Keripik::where('is_active', true)->count(),
            'totalStok' => Keripik::sum('stok'),
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('admin.keripik.create', ['categories' => KategoriKeripik::cases()]);
    }

    public function store(StoreKeripikRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['gambar_file']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('gambar_file')) {
            $path = $request->file('gambar_file')->store('keripik', 'public');
            $data['gambar_url'] = asset('storage/'.$path);
        }

        Keripik::create($data);

        return to_route('admin.keripik.index')->with('success', 'Produk keripik berhasil ditambahkan.');
    }

    public function show(Keripik $keripik): RedirectResponse
    {
        return to_route('admin.keripik.edit', $keripik);
    }

    public function edit(Keripik $keripik): View
    {
        return view('admin.keripik.edit', [
            'keripik' => $keripik,
            'categories' => KategoriKeripik::cases(),
        ]);
    }

    public function update(UpdateKeripikRequest $request, Keripik $keripik): RedirectResponse
    {
        $data = $request->safe()->except(['gambar_file']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('gambar_file')) {
            // Delete old uploaded file if stored locally
            if ($keripik->gambar_url && str_contains($keripik->gambar_url, '/storage/keripik/')) {
                $oldPath = str_replace(asset('storage/'), '', $keripik->gambar_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('gambar_file')->store('keripik', 'public');
            $data['gambar_url'] = asset('storage/'.$path);
        }

        $keripik->update($data);

        return to_route('admin.keripik.index')->with('success', 'Data produk keripik berhasil diperbarui.');
    }

    public function destroy(Keripik $keripik): RedirectResponse
    {
        if ($keripik->gambar_url && str_contains($keripik->gambar_url, '/storage/keripik/')) {
            $oldPath = str_replace(asset('storage/'), '', $keripik->gambar_url);
            Storage::disk('public')->delete($oldPath);
        }

        $keripik->delete();

        return to_route('admin.keripik.index')->with('success', 'Produk keripik berhasil dihapus.');
    }
}
