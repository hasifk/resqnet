<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;
class PaymentCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $information = [
            [
                'account_id'          => 'ASAFkAE-k8FSWQnMrbhkAw6tBDO4S-4qt7AyaZZxFyY7vJGPQmKwdKYY0yzBRf5ajJp6U1qoFQeQ3B8W',
                'payment_type'          => 'Paypal',
                'payment_amount'          => '2.99',
                'currency'          => 'USD',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table('payment_credentials')->insert($information);


    }
}
