<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Menu;

class MenuTest extends TestCase
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

    public function test_can_create_menu(): void
    {
        $response = $this->post(route('menus.store'), [
            'label' => 'About Us',
            'url' => '/about',
            'position' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('menus.index'));
        $this->assertDatabaseHas('menus', [
            'label' => 'About Us',
            'url' => '/about',
            'position' => 1,
            'is_active' => 1,
        ]);
    }

    public function test_can_update_menu(): void
    {
        $menu = Menu::create([
            'label' => 'About Us',
            'url' => '/about',
            'position' => 1,
            'is_active' => 1,
        ]);

        $response = $this->put(route('menus.update', $menu), [
            'label' => 'Contact Us',
            'url' => '/contact',
            'position' => 2,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('menus.index'));
        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'label' => 'Contact Us',
            'url' => '/contact',
            'position' => 2,
        ]);
    }

    public function test_can_delete_menu(): void
    {
        $menu = Menu::create([
            'label' => 'About Us',
            'url' => '/about',
            'position' => 1,
            'is_active' => 1,
        ]);

        $response = $this->delete(route('menus.destroy', $menu));

        $this->assertDatabaseMissing('menus', [
            'id' => $menu->id,
        ]);
    }

    public function test_menus_ordered_by_position(): void
    {
        $menu2 = Menu::create(['label' => 'Label Two', 'url' => '/2', 'position' => 2, 'is_active' => 1]);
        $menu0 = Menu::create(['label' => 'Label Zero', 'url' => '/0', 'position' => 0, 'is_active' => 1]);
        $menu1 = Menu::create(['label' => 'Label One', 'url' => '/1', 'position' => 1, 'is_active' => 1]);

        $orderedMenus = Menu::orderBy('position')->get();

        $this->assertEquals('Label Zero', $orderedMenus[0]->label);
        $this->assertEquals('Label One', $orderedMenus[1]->label);
        $this->assertEquals('Label Two', $orderedMenus[2]->label);
    }
}
