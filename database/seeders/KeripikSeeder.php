<?php

namespace Database\Seeders;

use App\Models\Keripik;
use Illuminate\Database\Seeder;

class KeripikSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'nama' => 'Keripik Apel Manalagi Super',
                'kategori' => 'Keripik Buah',
                'harga' => 25000,
                'stok' => 100,
                'berat' => '100 gram',
                'deskripsi' => 'Apel Manalagi pilihan Kota Batu. Renyah, manis asam segar alami tanpa pemanis buatan dengan teknologi vacuum frying.',
                'gambar_url' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
            ],
            [
                'nama' => 'Keripik Nangka Madu',
                'kategori' => 'Keripik Buah',
                'harga' => 28000,
                'stok' => 85,
                'berat' => '100 gram',
                'deskripsi' => 'Aroma harum dan manis legit alami nangka madu tropis dengan tekstur garing renyah.',
                'gambar_url' => 'https://images.unsplash.com/photo-1587132137056-bfbf0166836e?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
            ],
            [
                'nama' => 'Keripik Tempe Daun Jeruk',
                'kategori' => 'Keripik Tempe & Gurih',
                'harga' => 18000,
                'stok' => 120,
                'berat' => '150 gram',
                'deskripsi' => 'Tempe kedelai khas Malang dengan irisan renyah dan bumbu rempah daun jeruk harum.',
                'gambar_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
            ],
            [
                'nama' => 'Keripik Salak Pondoh',
                'kategori' => 'Keripik Buah',
                'harga' => 24000,
                'stok' => 60,
                'berat' => '100 gram',
                'deskripsi' => 'Kombinasi rasa manis segar buah salak pondoh dalam gigitan renyah berongga.',
                'gambar_url' => 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
            ],
            [
                'nama' => 'Keripik Tempe Balado Pedas',
                'kategori' => 'Keripik Tempe & Gurih',
                'harga' => 19000,
                'stok' => 75,
                'berat' => '150 gram',
                'deskripsi' => 'Bumbu balado pedas manis gurih yang meresap sempurna pada keripik tempe renyah.',
                'gambar_url' => 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
            ],
            [
                'nama' => 'Paket Oleh-Oleh Arema (3 In 1)',
                'kategori' => 'Paket Bundling & Hampers',
                'harga' => 65000,
                'stok' => 40,
                'berat' => '350 gram',
                'deskripsi' => '1x Keripik Apel + 1x Keripik Nangka + 1x Keripik Tempe Daun Jeruk + Free Tas Jinjing.',
                'gambar_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80',
                'is_active' => true,
            ],
        ];

        foreach ($products as $prod) {
            Keripik::updateOrCreate(['nama' => $prod['nama']], $prod);
        }
    }
}
