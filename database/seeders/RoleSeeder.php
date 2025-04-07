<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'admin']);
        $authorRole = Role::firstOrCreate(['name' => 'author']);
        // $subscriberRole = Role::firstOrCreate(['name' => 'subscriber']);

        // Create an admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'], // Check for existing user by email
            [
                'name' => 'Super Admin',
                'password' => bcrypt('admin'), // Encrypt password
            ]
        );

        // Assign role to the user
        if (!$user->hasRole('admin')) {
            $user->assignRole($superAdminRole);
            dd(0);
        }

         // Create an author user
         $authorUser = User::firstOrCreate(
            ['email' => 'author@gmail.com'], // Check for existing user by email
            [
                'name' => 'Author User',
                'password' => bcrypt('author'), // Encrypt password
            ]
        );

        // Assign role to the author user
        if (!$authorUser->hasRole('author')) {
            $authorUser->assignRole($authorRole);
        }

    }
}
