<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class MediaTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'editor']);

        $this->user = User::factory()->create();
        $this->user->assignRole('editor');
        $this->actingAs($this->user);
    }

    public function test_can_upload_media(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('photo.jpg');

        $response = $this->post(route('media.store'), [
            'file' => $image,
        ]);

        $response->assertRedirect(route('media.index'));

        $media = Media::first();
        $this->assertNotNull($media);
        $this->assertEquals('image', $media->file_type);

        Storage::disk('public')->assertExists($media->file_path);
    }

    public function test_can_delete_own_media(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('photo.jpg');
        $storedPath = $image->store('media', 'public');

        $media = Media::create([
            'file_name' => 'photo.jpg',
            'file_path' => $storedPath,
            'file_type' => 'image',
            'uploaded_by' => $this->user->id,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $response = $this->delete(route('media.destroy', $media));

        $this->assertDatabaseMissing('media', [
            'id' => $media->id,
        ]);

        Storage::disk('public')->assertMissing($storedPath);
    }
}
