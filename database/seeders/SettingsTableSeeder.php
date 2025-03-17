<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'blog_name' => 'BLOGNEST',
            'description' => 'This is the description of BlogNest blog.',
            'logo_image' => 'blog logo.jpg', // Example, add the file in public/uploads
            'favicon_image' => 'like-icon.ico', // Example, add the file in public/uploads
            'social_links' => json_encode([
                'facebook' => 'https://facebook.com',
                'twitter' => 'https://twitter.com',
            ]),
        ]);
    }
}
