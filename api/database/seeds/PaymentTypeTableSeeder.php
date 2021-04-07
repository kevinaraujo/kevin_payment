<?php

use Illuminate\Database\Seeder;
use \App\Models\PaymentType;

class PaymentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentType::create([
            'code' => 'wl',
            'description' => 'Wallet'
        ]);

        PaymentType::create([
            'code' => 'dc',
            'description' => 'Debit Card'
        ]);

        PaymentType::create([
            'code' => 'cc',
            'description' => 'Credit Card'
        ]);
    }
}
