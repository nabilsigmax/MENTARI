<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keripiks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori');
            $table->decimal('harga', 15, 2);
            $table->unsignedInteger('stok')->default(0);
            $table->string('berat', 50);
            $table->text('deskripsi')->nullable();
            $table->string('gambar_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keripiks');
    }
};
