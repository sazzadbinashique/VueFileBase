<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@futurebridge.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@futurebridge.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        Project::create([
            'title' => 'Clean Water Initiative',
            'slug' => 'clean-water-initiative',
            'description' => 'Bringing clean drinking water to communities in need through well drilling and water filtration systems.',
            'goal_amount' => 50000, 'collected_amount' => 32500,
            'status' => 'active',
            'featured_image' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=400',
            'start_date' => '2026-01-01', 'end_date' => '2026-12-31',
        ]);

        Project::create([
            'title' => 'Education for All',
            'slug' => 'education-for-all',
            'description' => 'Building schools and providing educational resources to underprivileged children.',
            'goal_amount' => 75000, 'collected_amount' => 45000,
            'status' => 'active',
            'featured_image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=400',
            'start_date' => '2026-02-01', 'end_date' => '2026-11-30',
        ]);

        Project::create([
            'title' => 'Healthcare Access',
            'slug' => 'healthcare-access',
            'description' => 'Providing medical supplies, clinics, and healthcare services to remote areas.',
            'goal_amount' => 100000, 'collected_amount' => 78000,
            'status' => 'active',
            'featured_image' => 'https://images.unsplash.com/photo-1551076805-e1869033e561?w=400',
            'start_date' => '2026-03-01', 'end_date' => '2026-10-31',
        ]);

        CmsPage::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => '<h1>About FutureBridge Foundation</h1><p>We are a non-profit organization dedicated to building bridges to a better future through community development, education, and healthcare initiatives.</p>',
            'status' => 'published',
        ]);
    }
}
