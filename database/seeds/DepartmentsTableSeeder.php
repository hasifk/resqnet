<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;
class DepartmentsTableSeeder extends Seeder
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
                'rescuertype_id'          => '1',
                'department'          => 'Police Station',

                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'rescuertype_id'          => '2',
                'department'          => 'Hospital',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'rescuertype_id'          => '2',
                'department'          => 'Medical Centre',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'rescuertype_id'          => '3',
                'department'          => 'Fire Department',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table('departments')->insert($information);


    }

}
