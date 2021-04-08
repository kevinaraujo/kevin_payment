<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentType;
use App\Models\UserPayment;
use App\Models\User;

class UserPaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            $paymentTypes = PaymentType::all();

            foreach ($paymentTypes as $paymentType) {

                UserPayment::create([
                    'user_id' => $user->id,
                    'payment_type_id' => $paymentType->id
                ]);
            }
        }
    }
}
