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

        CmsPage::updateOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => 'About Us',
                'content' => '<h1>About FutureBridge Foundation</h1><p>We are a non-profit organization dedicated to building bridges to a better future through community development, education, and healthcare initiatives.</p>',
                'status' => 'published',
            ]
        );

        CmsPage::updateOrCreate(
            ['slug' => 'projects'],
            [
                'title' => 'Projects',
                'title_bn' => 'প্রকল্প',
                'content' => 'Projects page content',
                'content_bn' => 'প্রকল্প পেজ কনটেন্ট',
                'banner_eyebrow' => 'Where your support goes',
                'banner_eyebrow_bn' => 'আপনার সহায়তা যেখানে যায়',
                'banner_title' => 'Our Projects',
                'banner_title_bn' => 'আমাদের প্রকল্পসমূহ',
                'banner_description' => 'Every donation goes directly to program costs. Pick a cause that speaks to you.',
                'banner_description_bn' => 'প্রত্যেকটি অনুদান সরাসরি কর্মসূচির খরচে ব্যয় হয়। আপনার পছন্দের একটি কারণ বেছে নিন।',
                'status' => 'published',
            ]
        );

        CmsPage::updateOrCreate(
            ['slug' => 'videos'],
            [
                'title' => 'Videos',
                'title_bn' => 'ভিডিও',
                'content' => 'Videos page content',
                'content_bn' => 'ভিডিও পেজ কনটেন্ট',
                'banner_eyebrow' => 'See it in motion',
                'banner_eyebrow_bn' => 'চলমান চিত্রে দেখুন',
                'banner_title' => 'Our Videos',
                'banner_title_bn' => 'আমাদের ভিডিও',
                'banner_description' => 'Field visits, distribution days, and stories from the communities we work with.',
                'banner_description_bn' => 'মাঠ পরিদর্শন, বিতরণের দিন, আর আমাদের কাজের কমিউনিটিগুলোর গল্প।',
                'status' => 'published',
            ]
        );

        CmsPage::updateOrCreate(
            ['slug' => 'gallery'],
            [
                'title' => 'Gallery',
                'title_bn' => 'গ্যালারি',
                'content' => 'Gallery page content',
                'content_bn' => 'গ্যালারি পেজ কনটেন্ট',
                'banner_eyebrow' => 'Moments from the field',
                'banner_eyebrow_bn' => 'মাঠের মুহূর্তগুলো',
                'banner_title' => 'Gallery',
                'banner_title_bn' => 'গ্যালারি',
                'banner_description' => 'Click any photo for a closer look. Filter by project to see specific work.',
                'banner_description_bn' => 'বড় করে দেখতে যেকোনো ছবিতে ক্লিক করুন। নির্দিষ্ট কাজ দেখতে প্রকল্প অনুযায়ী ফিল্টার করুন।',
                'status' => 'published',
            ]
        );

        CmsPage::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact',
                'title_bn' => 'যোগাযোগ',
                'content' => 'Contact page content',
                'content_bn' => 'যোগাযোগ পেজ কনটেন্ট',
                'banner_eyebrow' => 'Contact',
                'banner_eyebrow_bn' => 'যোগাযোগ',
                'banner_title' => 'Get in touch',
                'banner_title_bn' => 'যোগাযোগ করুন',
                'banner_description' => 'Share your questions, partnership ideas, or volunteer interest and our team will respond soon.',
                'banner_description_bn' => 'আপনার প্রশ্ন, অংশীদারিত্বের ধারণা বা স্বেচ্ছাসেবী আগ্রহ জানাতে আমাদের লিখুন, আমরা দ্রুত উত্তর দেব।',
                'layout_json' => json_encode([
                    'details' => [
                        ['label' => 'Email', 'value' => 'hello@futurebridgefoundation.org'],
                        ['label' => 'Phone', 'value' => '+880 1XXX-XXXXXX'],
                        ['label' => 'Address', 'value' => 'Dhaka, Bangladesh'],
                    ],
                    'form' => [
                        'title' => 'Send us a message',
                        'description' => 'Our team typically replies within 1-2 business days.',
                    ],
                ]),
                'layout_json_bn' => json_encode([
                    'details' => [
                        ['label' => 'ইমেইল', 'value' => 'hello@futurebridgefoundation.org'],
                        ['label' => 'ফোন', 'value' => '+880 1XXX-XXXXXX'],
                        ['label' => 'ঠিকানা', 'value' => 'ঢাকা, বাংলাদেশ'],
                    ],
                    'form' => [
                        'title' => 'আমাদের বার্তা পাঠান',
                        'description' => 'সাধারণত ১-২ কর্মদিবসের মধ্যে আমরা উত্তর দিই।',
                    ],
                ]),
                'status' => 'published',
            ]
        );

        $this->call(ContentSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}
