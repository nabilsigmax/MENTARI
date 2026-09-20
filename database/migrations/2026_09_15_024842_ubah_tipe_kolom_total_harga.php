<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Ini akan menimpa tipe data lama menjadi bigInteger tanpa menghapus isi tabel
            $table->bigInteger('total_harga')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->integer('total_harga')->change();
        });
    }
};