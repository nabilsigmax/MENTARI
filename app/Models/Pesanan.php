<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    //
    use HasFactory;

    protected $guarded = ['id'];

    public function keripik()
    {
        return $this->belongsTo(Keripik::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'tanggal_dikirim' => 'date',
            'tanggal_diterima' => 'date',
            'estimasi_tiba' => 'date',
        ];
    }
}
