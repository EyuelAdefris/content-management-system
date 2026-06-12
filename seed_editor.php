<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;
use App\Models\User;

// Set SQLite busy timeout to 10 seconds (10000 ms)
$pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
$pdo->exec('PRAGMA busy_timeout = 10000');

try {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $editorRole = Role::firstOrCreate(['name' => 'editor']);

    $user = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        ['name' => 'Admin User', 'password' => 'password']
    );
    $user->syncRoles([$adminRole]);

    $editor = User::firstOrCreate(
        ['email' => 'editor@example.com'],
        ['name' => 'Editor User', 'password' => 'password']
    );
    $editor->syncRoles([$editorRole]);

    echo "Seeding completed successfully!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
