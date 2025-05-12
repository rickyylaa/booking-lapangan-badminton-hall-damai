<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::factory(50)->create()->each(function ($transaction) {
            $customer = Customer::role('customer')->where('phone', $transaction->customer_phone)->first();
            $field = Field::where('type', $transaction->field)->first();

            TransactionDetail::factory(1)->create([
            ]);
        });
    }
}
