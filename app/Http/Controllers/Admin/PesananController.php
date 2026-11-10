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

        $data = $request->only([
            'status',
            'nomor_resi',
            'tanggal_dikirim',
            'tanggal_diterima',
            'estimasi_tiba',
        ]);

        $status = $data['status'] ?? $pesanan->status;

        if ($status === 'selesai' && empty($data['tanggal_diterima'])) {
            $data['tanggal_diterima'] = now()->toDateString();
        }

        if ($status === 'dikirim' && empty($data['tanggal_dikirim'])) {
            $data['tanggal_dikirim'] = now()->toDateString();
        }

        $pesanan->update($data);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
