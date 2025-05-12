<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Touché',
            'email' => 'touche@gmail.com',
            'password' => bcrypt('mamat1234'),
            'image' => 'touche.gif',
            'phone' => '087789616639',
            'address' => 'Jalan Maulana Malik Ibrahim',
            'status' => 1,
        ]);
    }
}
