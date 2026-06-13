<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Page;

class PageTest extends TestCase
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

    public function test_can_create_page(): void
    {
        $response = $this->post(route('pages.store'), [
            'title' => 'My New Page',
            'content' => 'This is the page content.',
            'status' => 'draft',
        ]);

        $response->assertRedirect(route('pages.index'));
        $this->assertDatabaseHas('pages', [
            'title' => 'My New Page',
            'slug' => 'my-new-page',
            'content' => 'This is the page content.',
            'status' => 'draft',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_page_creation_fails_without_title(): void
    {
        $response = $this->post(route('pages.store'), [
            'content' => 'This is the page content.',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_can_update_page(): void
    {
        $page = Page::create([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'content' => 'Old content',
            'status' => 'draft',
            'created_by' => $this->user->id,
        ]);

        $response = $this->put(route('pages.update', $page), [
            'title' => 'New Title',
            'content' => 'Updated content',
            'status' => 'draft',
        ]);

        $response->assertRedirect(route('pages.index'));
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'New Title',
            'slug' => 'new-title',
        ]);
    }

    public function test_can_delete_page(): void
    {
        $page = Page::create([
            'title' => 'Title to Delete',
            'slug' => 'title-to-delete',
            'content' => 'Some content',
            'status' => 'draft',
            'created_by' => $this->user->id,
        ]);

        $response = $this->delete(route('pages.destroy', $page));

        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }
}
