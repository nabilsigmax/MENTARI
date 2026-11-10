<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Keripik;
use Illuminate\View\View;

class KeripikController extends Controller
{
    public function show(Keripik $keripik): View
    {
        $kategoriValid = Kategori::where('nama', $keripik->kategori)->exists();

        abort_unless($keripik->is_active && $kategoriValid, 404);

        return view('keripik.show', ['keripik' => $keripik]);
    }
}
