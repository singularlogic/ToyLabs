<?php

use App\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentType::create(['name' => 'Bank Transfer']);
        PaymentType::create(['name' => 'Credit Card']);
        PaymentType::create(['name' => 'Paypal']);
        PaymentType::create(['name' => 'Check']);
        PaymentType::create(['name' => 'Bitcoin']);
    }
}
