<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class UserTableSeeder
 */
class UserTableSeeder extends Seeder {

    public function run() {
        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.users_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('DELETE FROM ' . config('access.users_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . config('access.users_table') . ' CASCADE');
        }

        //Add the master administrator, user id of 1
        $users = [
            [
                'firstname' => 'Admin Istrator',
                'lastname' => 'Admin',
                'email' => 'admin@admin.com',
                'country_id' => '101',
                'area_id' => '1930',
                'dept_name' => '',
                'per_lat' => '9.9312328',
                'per_lng' => '76.2673041',
                'per_address' => 'India,Kerala,Kochi',
                'password' => bcrypt('adminpassword'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Police1',
                'lastname' => 'Rescuer',
                'email' => 'police@police.com',
                'country_id' => '101',
                'area_id' => '1930',
                'dept_name' => "Hospital",
                'per_lat' => '9.9312328',
                'per_lng' => '76.2673041',
                'per_address' => 'India,Kerala,Kochi',
                'password' => bcrypt('asdasd'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Default User',
                'lastname' => 'Default',
                'email' => 'user@user.com',
                'country_id' => '101',
                'area_id' => '1930',
                'dept_name' => '',
                'per_lat' => '9.9312328',
                'per_lng' => '76.2673041',
                'per_address' => 'India,Kerala,Kochi',
                'password' => bcrypt('asdasd'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table(config('access.users_table'))->insert($users);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

}
