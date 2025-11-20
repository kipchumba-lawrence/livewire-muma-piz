<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdditionalUsersSeeder extends Seeder
{
    public function run()
    {
        // Create Editor User
        if (!User::where('email', 'editor@mumaapix.com')->exists()) {
            User::create([
                'name' => 'Editor',
                'email' => 'editor@mumaapix.com',
                'password' => Hash::make('secret'),
                'role' => 'editor', 
            ]);
        }

        // Create Photographer User
        if (!User::where('email', 'photographer@mumaapix.com')->exists()) {
            User::create([
                'name' => 'Photographer',
                'email' => 'photographer@mumaapix.com',
                'password' => Hash::make('secret'),
                'role' => 'photo',
            ]);
        }
    }
}
