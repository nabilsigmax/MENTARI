<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Keripik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_kategori_index(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.kategori.index'))
            ->assertOk()
            ->assertSee('Keripik Buah');
    }

    public function test_admin_can_add_a_kategori_and_use_it_for_a_product(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.kategori.store'), ['nama' => 'Keripik Sayur'])
            ->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseHas('kategoris', ['nama' => 'Keripik Sayur']);

        $this->actingAs($admin)
            ->post(route('admin.keripik.store'), [
                'nama' => 'Keripik Bayam',
                'kategori' => 'Keripik Sayur',
                'harga' => 20000,
                'stok' => 10,
                'berat' => '100 gr',
            ])
            ->assertRedirect(route('admin.keripik.index'));

        $this->assertDatabaseHas('keripiks', [
            'nama' => 'Keripik Bayam',
            'kategori' => 'Keripik Sayur',
        ]);
    }

    public function test_kategori_name_must_be_unique(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.kategori.store'), ['nama' => 'Keripik Buah'])
            ->assertSessionHasErrors('nama');

        $this->assertSame(1, Kategori::where('nama', 'Keripik Buah')->count());
    }

    public function test_renaming_a_kategori_updates_its_products(): void
    {
        $kategori = Kategori::where('nama', 'Keripik Buah')->firstOrFail();
        $keripik = Keripik::factory()->create(['kategori' => 'Keripik Buah']);

        $this->actingAs($this->admin())
            ->put(route('admin.kategori.update', $kategori), ['nama' => 'Keripik Buah Segar'])
            ->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseHas('kategoris', ['nama' => 'Keripik Buah Segar']);
        $this->assertDatabaseHas('keripiks', [
            'id' => $keripik->id,
            'kategori' => 'Keripik Buah Segar',
        ]);
    }

    public function test_kategori_in_use_cannot_be_deleted(): void
    {
        $kategori = Kategori::where('nama', 'Keripik Buah')->firstOrFail();
        Keripik::factory()->create(['kategori' => 'Keripik Buah']);

        $this->actingAs($this->admin())
            ->delete(route('admin.kategori.destroy', $kategori))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('kategoris', ['id' => $kategori->id]);
    }

    public function test_new_kategori_appears_as_a_landing_page_filter(): void
    {
        Kategori::create(['nama' => 'Keripik Sayur']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Keripik Sayur');
    }

    public function test_unused_kategori_can_be_deleted(): void
    {
        $kategori = Kategori::create(['nama' => 'Kategori Kosong']);

        $this->actingAs($this->admin())
            ->delete(route('admin.kategori.destroy', $kategori))
            ->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseMissing('kategoris', ['id' => $kategori->id]);
    }
}
