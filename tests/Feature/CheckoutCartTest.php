<?php

namespace Tests\Feature;

use App\Models\Keripik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_keranjang_membuat_satu_pesanan_per_produk(): void
    {
        $user = User::factory()->create();
        $apel = Keripik::factory()->create(['nama' => 'Keripik Apel', 'harga' => 25000, 'stok' => 10]);
        $nangka = Keripik::factory()->create(['nama' => 'Keripik Nangka', 'harga' => 28000, 'stok' => 8]);

        $items = urlencode(json_encode([
            ['id' => $apel->id, 'qty' => 2],
            ['id' => $nangka->id, 'qty' => 1],
        ]));

        $this->actingAs($user)->get(route('checkout.cart')."?items={$items}")
            ->assertRedirect(route('checkout.index'));

        $this->actingAs($user)->get(route('checkout.index'))
            ->assertOk()
            ->assertSee('Keripik Apel')
            ->assertSee('Keripik Nangka');

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'items' => json_encode([
                ['keripik_id' => $apel->id, 'jumlah' => 2],
                ['keripik_id' => $nangka->id, 'jumlah' => 1],
            ]),
            'nama_pembeli' => 'Nabil',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 1, Malang',
            'metode_pembayaran' => 'COD',
        ]);

        $response->assertRedirect(route('checkout.success', 1));

        $this->assertDatabaseHas('pesanans', ['keripik_id' => $apel->id, 'jumlah' => 2, 'total_harga' => 50000]);
        $this->assertDatabaseHas('pesanans', ['keripik_id' => $nangka->id, 'jumlah' => 1, 'total_harga' => 28000]);
        $this->assertSame(8, $apel->fresh()->stok);
        $this->assertSame(7, $nangka->fresh()->stok);
    }

    public function test_checkout_keranjang_menolak_produk_tidak_dikenal(): void
    {
        $user = User::factory()->create();

        $items = urlencode(json_encode([['id' => 999999, 'qty' => 1]]));

        $this->actingAs($user)->get(route('checkout.cart')."?items={$items}")
            ->assertRedirect(route('home'));
    }

    public function test_checkout_satuan_tetap_berfungsi(): void
    {
        $user = User::factory()->create();
        $keripik = Keripik::factory()->create(['harga' => 20000, 'stok' => 5]);

        $this->actingAs($user)->get(route('checkout.index', ['keripik_id' => $keripik->id, 'qty' => 2]))
            ->assertOk();

        $this->actingAs($user)->post(route('checkout.store'), [
            'keripik_id' => $keripik->id,
            'jumlah' => 2,
            'nama_pembeli' => 'Nabil',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 1, Malang',
            'metode_pembayaran' => 'COD',
        ])->assertRedirect(route('checkout.success', 1));

        $this->assertDatabaseHas('pesanans', ['keripik_id' => $keripik->id, 'jumlah' => 2, 'total_harga' => 40000]);
    }
}
