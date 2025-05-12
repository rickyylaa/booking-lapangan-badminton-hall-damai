<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Field;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    private static $invoiceCounter = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        $customer = User::role('customer')->inRandomOrder()->first();
        $field = Field::inRandomOrder()->first();

        $invoice = 'TRC-' . str_pad(self::$invoiceCounter, 3, '0', STR_PAD_LEFT);
        self::$invoiceCounter++;

        $dateTime = $faker->dateTimeBetween('-1 year', 'now');
        $dayOfWeek = date('l', strtotime($dateTime->format('d F Y')));

        $kilogram = $faker->numberBetween(1, 20);
        $price = $field->price * $kilogram;

        return [
            'invoice' => $invoice,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'field' => $field->type,
            'day' => $dayOfWeek,
            'date' => $dateTime->format('d F Y'),
            'kilogram' => $kilogram,
            'price' => $price,
            'status' => $faker->numberBetween(0, 4),
            'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
