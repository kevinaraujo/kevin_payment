<?php

use Illuminate\Database\Seeder;
use \App\Models\UserType;

class UserTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::create([
            'code' => 'shopkeeper',
            'description' => 'Shopkeeper profile',
            'sends_money' => false
        ]);

        UserType::create([
            'code' => 'client',
            'description' => 'Client profile',
            'sends_money' => true
        ]);
    }
}
