<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;
class RescuerTypeSeeder extends Seeder
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
                'type'          => 'Police',
                'display_name'          => 'Police Officer',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'type'          => 'Paramedic',
                'display_name'          => 'Paramedic',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'type'          => 'Fire',
                'display_name'          => 'Firemen',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table('rescuertypes')->insert($information);


      }
}
