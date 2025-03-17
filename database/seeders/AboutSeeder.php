<?php


namespace Database\Seeders;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('abouts')->insert([
            'image' => 'default.png', // Default image ka naam
            'name' => 'About Us', // Blog ka naam
            'description' => 'This is the about section description.', // Description
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
