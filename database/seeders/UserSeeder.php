<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ricky Fajar Adiputraa',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('1'),
            'image' => 'rickyylaa.gif',
            'phone' => '087789616639',
            'address' => 'Jalan Maulana Malik Ibrahim',
            'status' => 1,
        ]);
    }
}
