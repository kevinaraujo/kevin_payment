<?php

use Illuminate\Database\Seeder;
use \Faker\Provider\pt_BR\Person;
use \Faker\Provider\pt_BR\Company;
use \App\Models\UserType;
use \App\Models\User;
use \Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new Person($faker));
        $faker->addProvider(new Company($faker));

        $userType = UserType::where('code', 'shopkeeper')->first();

        User::create([
            'name' => $faker->name,
            'email' => 'shopkeeper@payment.com',
            'password' => Hash::make(12345678),
            'document' => $faker->cnpj(false),
            'balance' => 1100,
            'user_type_id' => $userType->id
        ]);

        $userType = UserType::where('code', 'client')->first();

        User::create([
            'name' => $faker->name,
            'email' => 'client@payment.com',
            'password' => Hash::make($faker->password),
            'document' => $faker->cpf(false),
            'balance' => 900,
            'user_type_id' => $userType->id
        ]);
    }
}
