<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AboutSeeder::class);
        $this->call([
            RoleSeeder::class, // Isko yahan add karen
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
