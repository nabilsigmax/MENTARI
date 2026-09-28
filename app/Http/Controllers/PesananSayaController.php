<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesananSayaController extends Controller
{
    /**
     * Menampilkan semua riwayat pesanan milik user yang sedang login
     */
    public function index(): View
    {
        // Tarik semua data pesanan milik user yang login beserta relasi keripiknya,
        // diurutkan dari yang paling baru (latest)
        $pesanans = Pesanan::with('keripik')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Kembalikan ke view
        return view('pesanan-saya.index', [
            'pesanans' => $pesanans,
        ]);
    }
}
