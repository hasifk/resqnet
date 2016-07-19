<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertPoliceRolePermission();
        $this->insertFireRolePermission();
        $this->insertAmbulanceRolePermission();
    }

    public function insertPoliceRolePermission(){
        $permissions = ['view-backend', 'view-access-management'];

        $resquer_role_id = DB::table('roles')->where('name', 'Police')->value('id');

        foreach ($permissions as $item) {

            $permission_id = DB::table('permissions')->where('name', $item)->value('id');

            DB::table( config('access.permission_role_table') )->insert([
                'permission_id'     => $permission_id,
                'role_id'           => $resquer_role_id
            ]);
        }
    }

    public function insertFireRolePermission(){
        $permissions = ['view-backend', 'view-access-management'];

        $resquer_role_id = DB::table('roles')->where('name', 'Fire')->value('id');

        foreach ($permissions as $item) {

            $permission_id = DB::table('permissions')->where('name', $item)->value('id');

            DB::table( config('access.permission_role_table') )->insert([
                'permission_id'     => $permission_id,
                'role_id'           => $resquer_role_id
            ]);
        }
    }

    public function insertAmbulanceRolePermission(){
        $permissions = ['view-backend', 'view-access-management'];

        $resquer_role_id = DB::table('roles')->where('name', 'Ambulance')->value('id');

        foreach ($permissions as $item) {

            $permission_id = DB::table('permissions')->where('name', $item)->value('id');

            DB::table( config('access.permission_role_table') )->insert([
                'permission_id'     => $permission_id,
                'role_id'           => $resquer_role_id
            ]);
        }
    }





}
