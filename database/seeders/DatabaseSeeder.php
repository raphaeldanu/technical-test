<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $testAdmin = User::create([
            'name' => "Raphael Adhimas",
            'username' => 'raphaeldanu',
            'password' => Hash::make('12345678'),
        ]);

        $testUser = User::create([
            'name' => "Test User",
            'username' => 'testuser',
            'password' => Hash::make('12345678'),
        ]);

        $admin = Role::create(['name' => "Administrator"]);
        $editor = Role::create(['name' => "Editor"]);

        $permissions = [
            'access-users',
            'access-genres',
            'access-books',
        ];

        foreach ($permissions as $permission){
            $permit = Permission::create(['name' => $permission]);
            $admin->givePermissionTo($permit);
        }

        $editor->givePermissionTo(3);

        $testAdmin->assignRole($admin);
        $testUser->assignRole($editor);

        $genres = [
            'Romantis',
            'Fanfiction',
            'Science Fiction',
            'Fantasi',
            'Historical',
            'Horor',
            'Biografi',
            'Auto Biografi'
        ];

        foreach ($genres as $item ) {
            Genre::create(['name' => $item]);
        }

        $this->run([
            BookSeeder::class
        ]);
    }
}
