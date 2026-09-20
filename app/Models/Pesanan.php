<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    //
    use HasFactory;

    protected $guarded = ['id'];

    public function keripik()
    {
        return $this->belongsTo(Keripik::class);
    }
}
