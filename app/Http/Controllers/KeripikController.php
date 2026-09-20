<?php

namespace App\Http\Controllers;

use App\KategoriKeripik;
use App\Models\Keripik;
use Illuminate\View\View;

class KeripikController extends Controller
{
    public function show(Keripik $keripik): View
    {
        abort_unless($keripik->is_active && in_array($keripik->kategori, KategoriKeripik::values(), true), 404);

        return view('keripik.show', ['keripik' => $keripik]);
    }
}
