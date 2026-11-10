<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        $now = now();

        DB::table('kategoris')->insertOrIgnore(
            array_map(
                static fn (string $nama): array => [
                    'nama' => $nama,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                ['Keripik Buah', 'Keripik Tempe & Gurih', 'Paket Bundling & Hampers'],
            ),
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};
