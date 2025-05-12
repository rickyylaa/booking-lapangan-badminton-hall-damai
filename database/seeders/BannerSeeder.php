<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'title' => 'Ready to play badminton?',
            'image' => 'badminton-court.jpg',
            'description' => 'Ready to play badminton?',
            'status' => 1
        ]);
    }
}
