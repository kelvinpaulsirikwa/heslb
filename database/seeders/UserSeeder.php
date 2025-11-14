<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Userstable;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default user
        Userstable::create([
            'username' => 'Helsb Admin',
            'email' => 'heslb@admin.com',
            'password' => Hash::make('password'), // Laravel automatically encrypts this
            'profile_image' => 'images/static_files/nodp.png',
            'telephone' => '0754776980',
            'role' => 'admin',
        ]);

            }
}
