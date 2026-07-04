<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        // Helper to create user with role sync
        $createUser = function (array $attrs, string $roleName) {
            $user = User::firstOrCreate(
                ['email' => $attrs['email']],
                [
                    'name' => $attrs['name'],
                    'email' => $attrs['email'],
                    'password' => bcrypt($attrs['password'] ?? 'password'),
                    'role' => $roleName,
                    'status' => $attrs['status'] ?? true,
                ]
            );
            $role = \App\Models\Role::where('name', $roleName)->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        };

        $createUser(
            ['name' => 'Admin User', 'email' => 'admin@futurebridge.com'],
            'admin'
        );

        $createUser(
            ['name' => 'Manager User', 'email' => 'manager@futurebridge.com'],
            'manager'
        );

        $createUser(
            ['name' => 'Editor User', 'email' => 'editor@futurebridge.com'],
            'editor'
        );

        $createUser(
            ['name' => 'Test User', 'email' => 'user@futurebridge.com'],
            'user'
        );

        $createUser(
            ['name' => 'Sarah Rahman', 'email' => 'sarah@example.com'],
            'manager'
        );

        $createUser(
            ['name' => 'Karim Hossain', 'email' => 'karim@example.com'],
            'editor'
        );

        $createUser(
            ['name' => 'Fatima Begum', 'email' => 'fatima@example.com'],
            'user'
        );

        CmsPage::firstOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => 'About Us',
                'content' => '<h1>About FutureBridge Foundation</h1><p>We are a non-profit organization dedicated to building bridges to a better future through community development, education, and healthcare initiatives.</p>',
                'status' => 'published',
            ]
        );

        $this->call(ContentSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}
