<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Debit Card',
                'is_active' => 1,
            ],
            [
                'name' => 'PayPal',
                'is_active' => 1,
            ],
            [
                'name' => 'Bank Transfer',
                'is_active' => 1,
            ],
            [
                'name' => 'Apple Pay',
                'is_active' => 1,
            ],
            [
                'name' => 'Google Pay',
                'is_active' => 1,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
