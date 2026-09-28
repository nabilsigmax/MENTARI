<?php

namespace App\Http\Controllers;

use App\Models\Keripik;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    //
    /**
     * Menerima isi keranjang dari halaman utama lalu menyimpannya
     * ke session sebelum masuk ke halaman checkout.
     */
    public function cart(Request $request)
    {
        $items = json_decode((string) $request->query('items', '[]'), true);

        if (! is_array($items) || $items === []) {
            return redirect()->route('home')->with('error', 'Keranjang masih kosong.');
        }

        $checkoutItems = [];

        foreach ($items as $item) {
            $keripikId = $item['id'] ?? null;
            $jumlah = (int) ($item['qty'] ?? 0);

            $keripik = Keripik::where('id', $keripikId)->where('is_active', true)->first();

            if (! $keripik || $jumlah < 1) {
                return redirect()->route('home')->with('error', 'Ada produk di keranjang yang sudah tidak tersedia.');
            }

            if ($keripik->stok < $jumlah) {
                return redirect()->route('home')->with('error', "Stok {$keripik->nama} tidak mencukupi.");
            }

            $checkoutItems[] = ['keripik_id' => $keripik->id, 'jumlah' => $jumlah];
        }

        $request->session()->put('checkout_cart', $checkoutItems);

        return redirect()->route('checkout.index');
    }

    public function index(Request $request)
    {
        // Alur keranjang: beberapa produk sekaligus dari session
        if ($request->session()->has('checkout_cart')) {
            $sessionItems = $request->session()->get('checkout_cart');
            $items = [];
            $total = 0;

            foreach ($sessionItems as $sessionItem) {
                $keripik = Keripik::where('id', $sessionItem['keripik_id'])->where('is_active', true)->first();

                if (! $keripik || $keripik->stok < $sessionItem['jumlah']) {
                    $request->session()->forget('checkout_cart');

                    return redirect()->route('home')->with('error', 'Ada produk di keranjang yang sudah tidak tersedia.');
                }

                $items[] = ['keripik' => $keripik, 'jumlah' => $sessionItem['jumlah']];
                $total += $keripik->harga * $sessionItem['jumlah'];
            }

            return view('checkout.index', [
                'items' => $items,
                'total' => $total,
                'keripik' => $items[0]['keripik'],
                'qty' => $items[0]['jumlah'],
            ]);
        }

        // Alur satuan: satu produk langsung (tombol Beli Sekarang)
        $request->validate([
            'keripik_id' => 'required|exists:keripiks,id',
            'qty' => 'required|integer|min:1',
        ]);

        $keripik = Keripik::findOrFail($request->keripik_id);
        $qty = $request->qty;
        $total = $keripik->harga * $qty;

        return view('checkout.index', [
            'items' => [['keripik' => $keripik, 'jumlah' => $qty]],
            'keripik' => $keripik,
            'qty' => $qty,
            'total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keripik_id' => 'nullable|exists:keripiks,id',
            'jumlah' => 'nullable|integer|min:1',
            'items' => 'nullable|json',
            'nama_pembeli' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);

        // Kumpulkan daftar produk dari keranjang (multi-item) atau alur satuan
        $orderItems = [];

        if ($validated['items'] ?? null) {
            foreach (json_decode($validated['items'], true) as $item) {
                $orderItems[] = [
                    'keripik_id' => $item['keripik_id'],
                    'jumlah' => (int) $item['jumlah'],
                ];
            }
        } else {
            $request->validate([
                'keripik_id' => 'required|exists:keripiks,id',
                'jumlah' => 'required|integer|min:1',
            ]);

            $orderItems[] = [
                'keripik_id' => $validated['keripik_id'],
                'jumlah' => (int) $validated['jumlah'],
            ];
        }

        if ($orderItems === []) {
            return back()->with('error', 'Tidak ada produk untuk dipesan.');
        }

        $pesanans = [];

        foreach ($orderItems as $orderItem) {
            $keripik = Keripik::where('id', $orderItem['keripik_id'])->where('is_active', true)->firstOrFail();

            // Cek stok
            if ($keripik->stok < $orderItem['jumlah'] || $orderItem['jumlah'] < 1) {
                return back()->with('error', "Stok {$keripik->nama} tidak mencukupi.");
            }

            $pesanans[] = $pesanan = Pesanan::create([
                'keripik_id' => $keripik->id,
                'jumlah' => $orderItem['jumlah'],
                'nama_pembeli' => $validated['nama_pembeli'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'total_harga' => $keripik->harga * $orderItem['jumlah'],
                'status' => 'dibayar',
                'user_id' => auth()->id(),
            ]);

            // Kurangi stok
            $keripik->decrement('stok', $orderItem['jumlah']);
        }

        $request->session()->forget('checkout_cart');

        // Satu pesanan dibuat per produk; tampilkan struk pesanan pertama,
        // sisanya bisa dipantau di halaman Pesanan Saya.
        return redirect()->route('checkout.success', $pesanans[0]->id);
    }

    public function success(Pesanan $pesanan)
    {
        return view('checkout.success', compact('pesanan'));
    }
}
