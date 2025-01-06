<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Admin;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();

        Admin::factory()->create([
            'name' => fake()->name(),
            'email' => 'admin@mail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('1234'),
            // 'image' => 'assets/images/avatars/male-xs.png', // password
            'remember_token' => Str::random(10),
        ]);

        Admin::factory(10)->create();
    }
}
