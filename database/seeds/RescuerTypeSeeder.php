<?php

use Illuminate\Database\Seeder;

class RescuerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        $information = [
            [
                'type'          => 'Police',
            ],
            [
                'type'          => 'Paramedic',
            ],
            [
                'type'          => 'Fireman',
            ],
        ];

        DB::table('rescuertypes')->insert($information);


        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
      }
}
