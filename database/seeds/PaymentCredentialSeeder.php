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
                'account_id'          => 'AQFqPsspTOSfx-4Nx6R6aiyN4APBWBCafOChvI1kNkfZToPKuHZKc7NUsTk3SH25PFLeCeblT3K_sYNw',
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
