<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Eslam Ayman',
            'email' => 'eslamayman1214@gmail.com',
            'phone' => '+201027091255',
        ])->addMedia(public_path('avatars/Avatar.jpg'))
            ->preservingOriginal()
            ->toMediaCollection('avatars');
    }
}
