<?php

namespace Database\Factories;

use App\Models\Keripik;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Keripik> */
class KeripikFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => 'Keripik '.$this->faker->word(),
            'kategori' => $this->faker->randomElement(['Keripik Buah', 'Keripik Tempe & Gurih', 'Paket Bundling & Hampers']),
            'harga' => $this->faker->numberBetween(15000, 50000),
            'stok' => $this->faker->numberBetween(0, 100),
            'berat' => $this->faker->randomElement(['100 gr', '150 gr', '250 gr']),
            'deskripsi' => $this->faker->sentence(),
            'gambar_url' => $this->faker->imageUrl(),
            'is_active' => true,
        ];
    }
}
