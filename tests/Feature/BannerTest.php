<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class BannerTest extends TestCase
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

    public function test_can_create_banner(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('banner.jpg');

        $response = $this->post(route('banners.store'), [
            'title' => 'Main Banner',
            'image' => $image,
            'position' => 0,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('banners.index'));
        $this->assertDatabaseHas('banners', [
            'title' => 'Main Banner',
            'position' => 0,
            'is_active' => 1,
        ]);
    }

    public function test_banner_creation_fails_without_image(): void
    {
        $response = $this->post(route('banners.store'), [
            'title' => 'No Image Banner',
            'position' => 0,
            'is_active' => 1,
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_can_toggle_banner_active(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('banner.jpg');
        $storedPath = $image->store('banners', 'public');

        $banner = Banner::create([
            'title' => 'Active Banner',
            'image' => $storedPath,
            'position' => 1,
            'is_active' => true,
        ]);

        $response = $this->put(route('banners.update', $banner), [
            'title' => 'Active Banner',
            'position' => 1,
            'is_active' => 0,
        ]);

        $response->assertRedirect(route('banners.index'));
        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'is_active' => 0,
        ]);
    }

    public function test_can_delete_banner(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('banner.jpg');
        $storedPath = $image->store('banners', 'public');

        $banner = Banner::create([
            'title' => 'Banner to Delete',
            'image' => $storedPath,
            'position' => 2,
            'is_active' => true,
        ]);

        $response = $this->delete(route('banners.destroy', $banner));

        $this->assertDatabaseMissing('banners', [
            'id' => $banner->id,
        ]);
    }
}
