<?php

namespace App\Models;

use Database\Factories\KeripikFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'kategori', 'harga', 'stok', 'berat', 'deskripsi', 'gambar_url', 'is_active'])]
class Keripik extends Model
{
    /** @use HasFactory<KeripikFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'stok' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
