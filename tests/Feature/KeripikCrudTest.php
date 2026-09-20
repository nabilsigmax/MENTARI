<?php

namespace Tests\Feature;

use App\Models\Keripik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeripikCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_the_keripik_dashboard(): void
    {
        $keripik = Keripik::factory()->create(['nama' => 'Keripik Apel']);

        $this->get(route('admin.keripik.index'))
            ->assertOk()
            ->assertSee($keripik->nama);
    }

    public function test_admin_can_store_a_keripik(): void
    {
        $payload = [
            'nama' => 'Keripik Nangka',
            'kategori' => 'Keripik Buah',
            'harga' => 28000,
            'stok' => 24,
            'berat' => '100 gr',
            'deskripsi' => 'Renyah dan manis alami.',
            'gambar_url' => 'https://example.com/nangka.jpg',
            'is_active' => '1',
        ];

        $this->post(route('admin.keripik.store'), $payload)
            ->assertRedirect(route('admin.keripik.index'));

        $this->assertDatabaseHas('keripiks', [
            'nama' => 'Keripik Nangka',
            'harga' => 28000,
            'stok' => 24,
            'is_active' => 1,
        ]);
    }

    public function test_keripik_requires_essential_fields(): void
    {
        $this->post(route('admin.keripik.store'), [])
            ->assertSessionHasErrors(['nama', 'kategori', 'harga', 'stok', 'berat']);

        $this->assertDatabaseCount('keripiks', 0);
    }

    public function test_admin_can_update_and_delete_a_keripik(): void
    {
        $keripik = Keripik::factory()->create(['nama' => 'Keripik Lama']);

        $this->put(route('admin.keripik.update', $keripik), [
            'nama' => 'Keripik Baru',
            'kategori' => 'Keripik Buah',
            'harga' => 30000,
            'stok' => 10,
            'berat' => '150 gr',
            'is_active' => '0',
        ])->assertRedirect(route('admin.keripik.index'));

        $this->assertDatabaseHas('keripiks', ['id' => $keripik->id, 'nama' => 'Keripik Baru', 'is_active' => 0]);

        $this->delete(route('admin.keripik.destroy', $keripik))
            ->assertRedirect(route('admin.keripik.index'));

        $this->assertDatabaseMissing('keripiks', ['id' => $keripik->id]);
    }

    public function test_admin_cannot_store_an_unknown_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.keripik.store'), [
            'nama' => 'Produk Tidak Berkategori',
            'kategori' => 'mobil',
            'harga' => 25000,
            'stok' => 5,
            'berat' => '100 gr',
        ])
            ->assertSessionHasErrors([
                'kategori' => 'Pilih salah satu kategori produk yang tersedia.',
            ]);

        $this->assertDatabaseMissing('keripiks', ['nama' => 'Produk Tidak Berkategori']);
    }

    public function test_landing_page_hides_products_with_an_unknown_category(): void
    {
        Keripik::factory()->create([
            'nama' => 'Keripik Apel',
            'kategori' => 'Keripik Buah',
        ]);
        Keripik::factory()->create([
            'nama' => 'Porsche 911 gt3 rs',
            'kategori' => 'mobil',
        ]);

        $this->get(route('home'))
            ->assertSee('Keripik Apel')
            ->assertDontSee('Porsche 911 gt3 rs');
    }
}
