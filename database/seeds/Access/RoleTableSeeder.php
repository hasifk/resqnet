<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleTableSeeder
 */
class RoleTableSeeder extends Seeder
{
    public function run()
    {
        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.roles_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('DELETE FROM ' . config('access.roles_table'));
            DB::statement('UPDATE sqlite_sequence SET seq = 0 where name = ' ."'". config('access.roles_table')."'");

        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . config('access.roles_table') . ' CASCADE');
        }

        //Create admin role, id of 1
        $role_model        = config('access.role');
        $admin             = new $role_model;
        $admin->name       = 'Administrator';
        $admin->all        = true;
        $admin->sort       = 1;
        $admin->created_at = Carbon::now();
        $admin->updated_at = Carbon::now();
        $admin->save();

        //Rescuer roles
        //Police

        $role_model = config('access.role');
        $admin = new $role_model;
        $admin->name = 'Police';
        $admin->all = false;
        $admin->sort = 2;
        $admin->created_at = Carbon::now();
        $admin->updated_at = Carbon::now();
        $admin->save();

        //Fire
        $role_model = config('access.role');
        $admin = new $role_model;
        $admin->name = 'Fire';
        $admin->all = false;
        $admin->sort = 3;
        $admin->created_at = Carbon::now();
        $admin->updated_at = Carbon::now();
        $admin->save();

        //Paramedicals
        $role_model = config('access.role');
        $admin = new $role_model;
        $admin->name = 'Paramedic';
        $admin->all = false;
        $admin->sort = 4;
        $admin->created_at = Carbon::now();
        $admin->updated_at = Carbon::now();
        $admin->save();

        //User
        $role_model       = config('access.role');
        $user             = new $role_model;
        $user->name       = 'User';
        $user->sort       = 5;
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();
        $user->save();

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
