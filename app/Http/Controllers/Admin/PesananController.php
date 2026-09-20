<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Pesanan::with('keripik')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_pembeli', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pesanans = $query->paginate(10);
        return view('admin.pesanan.index', compact('pesanans'));
    }

    public function show(Pesanan $pesanan)
    {
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => 'required|in:pending,dibayar,diproses,dikirim,selesai,batal'
        ]);

        $pesanan->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
