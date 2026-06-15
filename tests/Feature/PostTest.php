<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PostTest extends TestCase
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

    public function test_can_create_post(): void
    {
        $response = $this->post(route('posts.store'), [
            'title' => 'My New Post',
            'content' => 'This is the post content.',
            'status' => 'draft',
        ]);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', [
            'title' => 'My New Post',
            'slug' => 'my-new-post',
            'content' => 'This is the post content.',
            'status' => 'draft',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_can_create_post_with_featured_image(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('test.jpg');

        $response = $this->post(route('posts.store'), [
            'title' => 'My Photo Post',
            'content' => 'Post with image.',
            'status' => 'draft',
            'featured_image' => $image,
        ]);

        $response->assertRedirect(route('posts.index'));

        // Retrieve the created post
        $post = Post::where('title', 'My Photo Post')->first();
        $this->assertNotNull($post);
        $this->assertNotNull($post->featured_image);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'featured_image' => $post->featured_image,
        ]);

        Storage::disk('public')->assertExists($post->featured_image);
    }

    public function test_post_creation_fails_without_content(): void
    {
        $response = $this->post(route('posts.store'), [
            'title' => 'Title Without Content',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_can_delete_post(): void
    {
        $post = Post::create([
            'title' => 'Post to Delete',
            'slug' => 'post-to-delete',
            'content' => 'Some content',
            'status' => 'draft',
            'created_by' => $this->user->id,
        ]);

        $response = $this->delete(route('posts.destroy', $post));

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_editor_cannot_edit_other_user_post(): void
    {
        $otherUser = User::factory()->create();
        $otherUser->assignRole('editor');

        $post = Post::create([
            'title' => 'Other User Post',
            'slug' => 'other-user-post',
            'content' => 'Some content',
            'status' => 'draft',
            'created_by' => $otherUser->id,
        ]);

        $response = $this->get(route('posts.edit', $post));
        $response->assertStatus(403);

        $response = $this->put(route('posts.update', $post), [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'draft',
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_can_edit_any_post(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $post = Post::create([
            'title' => 'Editor Post',
            'slug' => 'editor-post',
            'content' => 'Some content',
            'status' => 'draft',
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($admin)->get(route('posts.edit', $post));
        $response->assertStatus(200);

        $response = $this->put(route('posts.update', $post), [
            'title' => 'Updated by Admin',
            'content' => 'Updated content',
            'status' => 'draft',
        ]);
        $response->assertRedirect(route('posts.index'));
    }
}
