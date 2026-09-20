<?php

namespace App\Http\Controllers;

use App\Models\Keripik;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    //
    public function index(Request $request)
    {
        $request->validate([
            'keripik_id' => 'required|exists:keripiks,id',
            'qty' => 'required|integer|min:1',
        ]);

        $keripik = Keripik::findOrFail($request->keripik_id);
        $qty = $request->qty;
        $total = $keripik->harga * $qty;

        return view('checkout.index', compact('keripik', 'qty', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keripik_id' => 'required|exists:keripiks,id',
            'jumlah' => 'required|integer|min:1',
            'nama_pembeli' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $keripik = Keripik::findOrFail($validated['keripik_id']);
        
        // Cek stok
        if ($keripik->stok < $validated['jumlah']) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $validated['total_harga'] = $keripik->harga * $validated['jumlah'];
        $validated['status'] = 'pending';

        if ($request->hasFile('bukti_pembayaran')) {
            $validated['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $validated['status'] = 'dibayar'; // Kalau langsung upload bukti bayar
        }

        $pesanan = Pesanan::create($validated);

        // Kurangi stok
        $keripik->decrement('stok', $validated['jumlah']);

        return redirect()->route('checkout.success', $pesanan->id);
    }

    public function success(Pesanan $pesanan)
    {
        return view('checkout.success', compact('pesanan'));
    }
}
