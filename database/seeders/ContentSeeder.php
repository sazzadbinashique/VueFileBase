<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Video;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $food = Project::create([
            'title' => 'Food Donation',
            'title_bn' => 'খাদ্য সহায়তা',
            'slug' => 'food-donation',
            'icon' => 'utensils',
            'color' => 'accent',
            'description' => 'Warm meals and monthly ration bags for families who can\'t make ends meet.',
            'short_en' => 'Warm meals and monthly ration bags for families who can\'t make ends meet.',
            'short_bn' => 'যে পরিবারগুলোর মাসের শেষটা কষ্টে কাটে, তাদের জন্য গরম খাবার আর মাসিক রেশন ব্যাগ।',
            'body_en' => [
                'Every week our volunteers pack and deliver ration bags — rice, lentils, oil, and salt — to families across four districts who are one bad month away from going hungry.',
                'During Ramadan and flood season, we scale up to daily hot-meal distribution at community kitchens, feeding anyone who walks through the door, no questions asked.',
                'We work with local grocers and farmers first, which keeps costs low and puts money back into the same communities we serve.'
            ],
            'body_bn' => [
                'প্রতি সপ্তাহে আমাদের স্বেচ্ছাসেবকরা চাল, ডাল, তেল আর লবণ দিয়ে রেশন ব্যাগ প্যাক করে চারটি জেলার সেই পরিবারগুলোর কাছে পৌঁছে দেন, যাদের একটা খারাপ মাসই না খেয়ে থাকার কারণ হতে পারে।',
                'রমজান আর বন্যার মৌসুমে আমরা কমিউনিটি কিচেনে প্রতিদিনের গরম খাবার বিতরণ বাড়িয়ে দিই — যে কেউ এলেই খাবার পান, কোনো প্রশ্ন ছাড়াই।',
                'আমরা প্রথমে স্থানীয় মুদি দোকান আর কৃষকদের সাথে কাজ করি, এতে খরচ কম থাকে আর টাকাটাও একই কমিউনিটিতে ফিরে যায়, যাদের আমরা সেবা দিই।'
            ],
            'impact_en' => [
                ['value' => '48,000+', 'label' => 'meals served'],
                ['value' => '3,200', 'label' => 'families supported monthly'],
                ['value' => '4', 'label' => 'districts reached']
            ],
            'impact_bn' => [
                ['value' => '৪৮,০০০+', 'label' => 'বেলা খাবার বিতরণ'],
                ['value' => '৩,২০০', 'label' => 'পরিবার মাসিক সহায়তাপ্রাপ্ত'],
                ['value' => '৪', 'label' => 'জেলায় কার্যক্রম']
            ],
            'featured_image' => 'https://placehold.co/1200x800/0E6B5C/F3F7F1?text=Food+Donation',
            'status' => 'active',
            'goal_amount' => 50000,
            'collected_amount' => 32500,
        ]);

        $education = Project::create([
            'title' => 'Education Support',
            'title_bn' => 'শিক্ষা সহায়তা',
            'slug' => 'education-support',
            'icon' => 'book',
            'color' => 'primary',
            'description' => 'Scholarships, school supplies, and free tutoring so no child drops out over money.',
            'short_en' => 'Scholarships, school supplies, and free tutoring so no child drops out over money.',
            'short_bn' => 'বৃত্তি, স্কুল উপকরণ আর বিনামূল্যে টিউশন — যেন টাকার অভাবে কোনো শিশুর পড়াশোনা না থামে।',
            'body_en' => [
                'We cover tuition, books, and uniforms for children whose families would otherwise pull them out of school during hard times.',
                'Our after-school tutoring circles, run by university-student volunteers, help kids catch up in math and Bangla when classrooms are overcrowded.',
                'Every year we hand out fresh scholarships to top-performing students from our program so they can continue into secondary school and beyond.'
            ],
            'body_bn' => [
                'যে পরিবারগুলো কঠিন সময়ে সন্তানকে স্কুল থেকে সরিয়ে নিতে বাধ্য হতো, তাদের সন্তানদের বেতন, বই আর ইউনিফর্মের খরচ আমরা বহন করি।',
                'বিশ্ববিদ্যালয় ছাত্র-স্বেচ্ছাসেবকদের পরিচালিত আমাদের স্কুল-পরবর্তী টিউশন সার্কেল, ভিড়ে ঠাসা ক্লাসরুমে পিছিয়ে পড়া শিশুদের গণিত আর বাংলায় এগিয়ে আনতে সাহায্য করে।',
                'প্রতি বছর আমাদের প্রোগ্রামের সেরা শিক্ষার্থীদের নতুন বৃত্তি দেওয়া হয়, যাতে তারা মাধ্যমিক ও তার পরেও পড়াশোনা চালিয়ে যেতে পারে।'
            ],
            'impact_en' => [
                ['value' => '1,150', 'label' => 'students sponsored'],
                ['value' => '62', 'label' => 'scholarships this year'],
                ['value' => '18', 'label' => 'partner schools']
            ],
            'impact_bn' => [
                ['value' => '১,১৫০', 'label' => 'শিক্ষার্থী পৃষ্ঠপোষকতাপ্রাপ্ত'],
                ['value' => '৬২', 'label' => 'এই বছরের বৃত্তি'],
                ['value' => '১৮', 'label' => 'অংশীদার স্কুল']
            ],
            'featured_image' => 'https://placehold.co/1200x800/E8A93B/10302B?text=Education+Support',
            'status' => 'active',
            'goal_amount' => 35000,
            'collected_amount' => 28100,
        ]);

        $health = Project::create([
            'title' => 'Healthcare Assistance',
            'title_bn' => 'স্বাস্থ্যসেবা',
            'slug' => 'healthcare-assistance',
            'icon' => 'heart-pulse',
            'color' => 'accent2',
            'description' => 'Free medical camps, medicine, and emergency transport for families with nowhere else to turn.',
            'short_en' => 'Free medical camps, medicine, and emergency transport for families with nowhere else to turn.',
            'short_bn' => 'যাদের যাওয়ার আর কোনো জায়গা নেই, তাদের জন্য বিনামূল্যে চিকিৎসা ক্যাম্প, ওষুধ আর জরুরি পরিবহন।',
            'body_en' => [
                'Our monthly free health camps bring doctors, basic diagnostics, and medicine directly to villages that are hours from the nearest clinic.',
                'We run a small emergency fund that covers ambulance costs and urgent medicine for families who would otherwise skip treatment entirely.',
                'Maternal and child health is a priority — regular checkups, nutrition support, and safe-delivery guidance for expecting mothers.'
            ],
            'body_bn' => [
                'আমাদের মাসিক বিনামূল্যে স্বাস্থ্য ক্যাম্প নিকটতম ক্লিনিক থেকে ঘণ্টাখানেক দূরের গ্রামগুলোতে সরাসরি ডাক্তার, প্রাথমিক পরীক্ষা আর ওষুধ পৌঁছে দেয়।',
                'যে পরিবারগুলো চিকিৎসা এড়িয়ে যেত, তাদের জন্য আমরা একটি ছোট জরুরি তহবিল পরিচালনা করি, যা অ্যাম্বুলেন্স খরচ আর জরুরি ওষুধ বহন করে।',
                'মা ও শিশু স্বাস্থ্য আমাদের অগ্রাধিকার — গর্ভবতী মায়েদের জন্য নিয়মিত চেকআপ, পুষ্টি সহায়তা আর নিরাপদ প্রসবের পরামর্শ।'
            ],
            'impact_en' => [
                ['value' => '9,400+', 'label' => 'patients treated'],
                ['value' => '36', 'label' => 'free health camps'],
                ['value' => '210', 'label' => 'emergency cases funded']
            ],
            'impact_bn' => [
                ['value' => '৯,৪০০+', 'label' => 'রোগী চিকিৎসাপ্রাপ্ত'],
                ['value' => '৩৬', 'label' => 'বিনামূল্যে স্বাস্থ্য ক্যাম্প'],
                ['value' => '২১০', 'label' => 'জরুরি সহায়তা প্রদান']
            ],
            'featured_image' => 'https://placehold.co/1200x800/C43D3D/F3F7F1?text=Healthcare+Assistance',
            'status' => 'active',
            'goal_amount' => 60000,
            'collected_amount' => 44500,
        ]);

        $water = Project::create([
            'title' => 'Water & Sanitation',
            'title_bn' => 'পানি ও স্যানিটেশন',
            'slug' => 'water-sanitation',
            'icon' => 'droplet',
            'color' => 'primary',
            'description' => 'Safe tube-wells, latrines, and hygiene training for villages battling contaminated water.',
            'short_en' => 'Safe tube-wells, latrines, and hygiene training for villages battling contaminated water.',
            'short_bn' => 'দূষিত পানির সাথে লড়াই করা গ্রামগুলোর জন্য নিরাপদ নলকূপ, ল্যাট্রিন আর স্বাস্থ্যবিধি প্রশিক্ষণ।',
            'body_en' => [
                'We install and maintain arsenic-tested tube-wells in villages where the nearest safe water source used to be an hour\'s walk away.',
                'Our sanitation teams build low-cost latrines for households and schools, cutting waterborne illness dramatically in the areas we\'ve covered.',
                'Hygiene workshops for mothers and schoolchildren turn a one-time installation into a lasting habit.'
            ],
            'body_bn' => [
                'যেসব গ্রামে নিকটতম নিরাপদ পানির উৎস এক ঘণ্টার হাঁটাপথ ছিল, সেখানে আমরা আর্সেনিক-পরীক্ষিত নলকূপ স্থাপন ও রক্ষণাবেক্ষণ করি।',
                'আমাদের স্যানিটেশন দল বাড়ি আর স্কুলের জন্য কম খরচে ল্যাট্রিন তৈরি করে, যা আমাদের কার্যক্রম এলাকায় পানিবাহিত রোগ উল্লেখযোগ্যভাবে কমিয়ে দিয়েছে।',
                'মা ও স্কুলশিক্ষার্থীদের জন্য স্বাস্থ্যবিধি কর্মশালা একবারের স্থাপনাকে দীর্ঘস্থায়ী অভ্যাসে পরিণত করে।'
            ],
            'impact_en' => [
                ['value' => '76', 'label' => 'tube-wells installed'],
                ['value' => '1,900', 'label' => 'latrines built'],
                ['value' => '12,000+', 'label' => 'people with clean water access']
            ],
            'impact_bn' => [
                ['value' => '৭৬', 'label' => 'নলকূপ স্থাপিত'],
                ['value' => '১,৯০০', 'label' => 'ল্যাট্রিন নির্মিত'],
                ['value' => '১২,০০০+', 'label' => 'মানুষ নিরাপদ পানির সুবিধাপ্রাপ্ত']
            ],
            'featured_image' => 'https://placehold.co/1200x800/10302B/F3F7F1?text=Water+%26+Sanitation',
            'status' => 'active',
            'goal_amount' => 40000,
            'collected_amount' => 22300,
        ]);

        // Videos
        $videos = [
            ['title' => 'A Day at the Ration Drive', 'title_bn' => 'রেশন বিতরণের একদিন', 'description' => 'Follow our volunteers as they pack and deliver monthly ration bags.', 'description_bn' => 'স্বেচ্ছাসেবকরা কীভাবে মাসিক রেশন ব্যাগ প্যাক ও বিতরণ করেন।', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'project_id' => $food->id, 'sort_order' => 0],
            ['title' => 'Inside Our Tutoring Circles', 'title_bn' => 'টিউশন সার্কেলের ভিতরে', 'description' => 'University volunteers help students catch up after school.', 'description_bn' => 'বিশ্ববিদ্যালয় স্বেচ্ছাসেবকরা কীভাবে শিক্ষার্থীদের সাহায্য করেন।', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'project_id' => $education->id, 'sort_order' => 1],
            ['title' => 'Free Health Camp, Rangpur', 'title_bn' => 'বিনামূল্যে স্বাস্থ্য ক্যাম্প, রংপুর', 'description' => 'Doctors and medicine reach a village hours from the nearest clinic.', 'description_bn' => 'নিকটতম ক্লিনিক থেকে ঘণ্টাখানেক দূরের গ্রামে ডাক্তার ও ওষুধ।', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'project_id' => $health->id, 'sort_order' => 2],
            ['title' => 'Clean Water for Char Ishwardi', 'title_bn' => 'চর ঈশ্বরদীর জন্য নিরাপদ পানি', 'description' => 'Installing a new arsenic-tested tube-well in a riverside village.', 'description_bn' => 'নদীতীরের এক গ্রামে নতুন আর্সেনিক-পরীক্ষিত নলকূপ স্থাপন।', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'project_id' => $water->id, 'sort_order' => 3],
            ['title' => 'Scholarship Day 2025', 'title_bn' => 'বৃত্তি দিবস ২০২৫', 'description' => '62 students receive scholarships to continue their education.', 'description_bn' => '৬২ জন শিক্ষার্থী পড়াশোনা চালিয়ে যেতে বৃত্তি পেলেন।', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'project_id' => $education->id, 'sort_order' => 4],
            ['title' => 'Ramadan Iftar Kitchens', 'title_bn' => 'রমজানের ইফতার কিচেন', 'description' => 'Community kitchens serving hot iftar to anyone who needs it.', 'description_bn' => 'যে কারো জন্য গরম ইফতার পরিবেশনকারী কমিউনিটি কিচেন।', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'project_id' => $food->id, 'sort_order' => 5],
        ];

        foreach ($videos as $v) {
            Video::create($v + ['status' => true]);
        }

        // Gallery
        $gallery = [
            ['title' => 'Ration distribution day', 'title_bn' => 'রেশন বিতরণের দিন', 'image_path' => 'https://placehold.co/700x500/0E6B5C/F3F7F1?text=Ration+Day', 'project_id' => $food->id, 'sort_order' => 0],
            ['title' => 'A sponsored classroom', 'title_bn' => 'পৃষ্ঠপোষকতাপ্রাপ্ত ক্লাসরুম', 'image_path' => 'https://placehold.co/700x500/E8A93B/10302B?text=Classroom', 'project_id' => $education->id, 'sort_order' => 1],
            ['title' => 'Free health camp', 'title_bn' => 'বিনামূল্যে স্বাস্থ্য ক্যাম্প', 'image_path' => 'https://placehold.co/700x500/C43D3D/F3F7F1?text=Health+Camp', 'project_id' => $health->id, 'sort_order' => 2],
            ['title' => 'New tube-well installation', 'title_bn' => 'নতুন নলকূপ স্থাপন', 'image_path' => 'https://placehold.co/700x500/10302B/F3F7F1?text=Tube-well', 'project_id' => $water->id, 'sort_order' => 3],
            ['title' => 'Annual book drive', 'title_bn' => 'বার্ষিক বই সংগ্রহ', 'image_path' => 'https://placehold.co/700x500/E8A93B/10302B?text=Book+Drive', 'project_id' => $education->id, 'sort_order' => 4],
            ['title' => 'Our volunteer team', 'title_bn' => 'আমাদের স্বেচ্ছাসেবক দল', 'image_path' => 'https://placehold.co/700x500/0E6B5C/F3F7F1?text=Volunteers', 'project_id' => $food->id, 'sort_order' => 5],
            ['title' => 'Child vaccination drive', 'title_bn' => 'শিশু টিকাদান কর্মসূচি', 'image_path' => 'https://placehold.co/700x500/C43D3D/F3F7F1?text=Vaccination', 'project_id' => $health->id, 'sort_order' => 6],
            ['title' => 'Hygiene workshop', 'title_bn' => 'স্বাস্থ্যবিধি কর্মশালা', 'image_path' => 'https://placehold.co/700x500/10302B/F3F7F1?text=Hygiene+Talk', 'project_id' => $water->id, 'sort_order' => 7],
            ['title' => 'Scholarship award ceremony', 'title_bn' => 'বৃত্তি প্রদান অনুষ্ঠান', 'image_path' => 'https://placehold.co/700x500/E8A93B/10302B?text=Scholarship', 'project_id' => $education->id, 'sort_order' => 8],
            ['title' => 'Community iftar', 'title_bn' => 'কমিউনিটি ইফতার', 'image_path' => 'https://placehold.co/700x500/0E6B5C/F3F7F1?text=Iftar', 'project_id' => $food->id, 'sort_order' => 9],
            ['title' => 'Maternal health checkup', 'title_bn' => 'মাতৃস্বাস্থ্য পরীক্ষা', 'image_path' => 'https://placehold.co/700x500/C43D3D/F3F7F1?text=Mother+Care', 'project_id' => $health->id, 'sort_order' => 10],
            ['title' => 'Latrine construction', 'title_bn' => 'ল্যাট্রিন নির্মাণ', 'image_path' => 'https://placehold.co/700x500/10302B/F3F7F1?text=Latrine', 'project_id' => $water->id, 'sort_order' => 11],
        ];

        foreach ($gallery as $g) {
            GalleryImage::create($g + ['status' => true]);
        }
    }
}
