<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Field::create([
            'title' => 'Field 01',
            'slug' => 'field-01',
            'price' => '35000',
            'image' => 'field-01.jpg',
            'description' => 'Clean, cool badminton court and large parking lot',
            'condition' => 0,
            'status' => 1,
        ]);

        Field::create([
            'title' => 'Field 02',
            'slug' => 'field-02',
            'price' => '35000',
            'image' => 'field-02.jpg',
            'description' => 'Clean, cool badminton court and large parking lot',
            'condition' => 0,
            'status' => 1,
        ]);

        Field::create([
            'title' => 'Field 03',
            'slug' => 'field-03',
            'price' => '35000',
            'image' => 'field-03.jpg',
            'description' => 'Clean, cool badminton court and large parking lot',
            'condition' => 0,
            'status' => 1,
        ]);

        Field::create([
            'title' => 'Field 04',
            'slug' => 'field-04',
            'price' => '35000',
            'image' => 'field-04.jpg',
            'description' => 'Clean, cool badminton court and large parking lot',
            'condition' => 0,
            'status' => 1,
        ]);
    }
}
