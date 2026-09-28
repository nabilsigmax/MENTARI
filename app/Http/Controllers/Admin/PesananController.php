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
                ->orWhere('id', 'like', "%{$search}%")
                ->orWhere('nomor_resi', 'like', "%{$search}%");
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
            'status' => 'required|in:pending,dibayar,diproses,dikirim,selesai,batal',
            'nomor_resi' => 'nullable|string|max:100',
            'tanggal_dikirim' => 'nullable|date',
            'tanggal_diterima' => 'nullable|date|after_or_equal:tanggal_dikirim',
            'estimasi_tiba' => 'nullable|date|after_or_equal:tanggal_dikirim',
        ]);

        $pesanan->update([
            'status' => $request->status,
            'nomor_resi' => $request->nomor_resi,
            'tanggal_dikirim' => $request->tanggal_dikirim,
            'tanggal_diterima' => $request->tanggal_diterima,
            'estimasi_tiba' => $request->estimasi_tiba,
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
