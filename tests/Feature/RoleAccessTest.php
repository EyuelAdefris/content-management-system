<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'editor']);
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_editor_cannot_access_users_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');

        $this->actingAs($user)
            ->get('/dashboard/users')
            ->assertForbidden();
    }

    public function test_admin_can_access_users_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)
            ->get('/dashboard/users')
            ->assertOk();
    }
}
