<?php

namespace Tests\Feature;

use App\Models\Keripik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminKeripikTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_admin_keripik_index(): void
    {
        $user = User::factory()->create();
        Keripik::factory()->create(['nama' => 'Keripik Apel Batu']);

        $response = $this->actingAs($user)->get(route('admin.keripik.index'));

        $response->assertStatus(200);
        $response->assertSee('Keripik Apel Batu');
    }

    public function test_can_create_keripik_with_file_upload(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('apel.jpg', 600, 600);

        $response = $this->actingAs($user)->post(route('admin.keripik.store'), [
            'nama' => 'Keripik Apel Super',
            'kategori' => 'Keripik Buah',
            'harga' => 25000,
            'stok' => 50,
            'berat' => '100 gram',
            'deskripsi' => 'Renyah gurih nikmat.',
            'gambar_file' => $file,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.keripik.index'));

        $this->assertDatabaseHas('keripiks', [
            'nama' => 'Keripik Apel Super',
            'harga' => 25000,
        ]);

        $keripik = Keripik::where('nama', 'Keripik Apel Super')->first();
        $this->assertNotNull($keripik->gambar_url);
    }
}
