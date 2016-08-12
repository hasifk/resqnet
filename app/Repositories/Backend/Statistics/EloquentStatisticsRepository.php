<?php

namespace App\Repositories\Backend\Statistics;

use App\Models\Access\User\User;
use App\Models\Countries\City;
use App\Models\Countries\Country;
use App\Models\Rescuer\RescuerType;
use Auth;
use Storage;

class EloquentStatisticsRepository implements StatisticsRepositoryContract {

    public function getAmountOfUsers() {
        $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                ->whereRaw('assigned_roles.role_id = 5')
                ->count();
        return $amount;
    }

    public function getUserAmount($request) {
        if (!empty($request->state_id) && !empty($request->area_id)) {
            $country = City::where('id', $request->area_id)->value('name');
            $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                    ->where('users.area_id', $request->area_id)
                    ->whereRaw('assigned_roles.role_id = 5')
                    ->count();
        } else if (!empty($request->country_id)) {
            $country = Country::where('id', $request->country_id)->value('name');
            $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                    ->where('users.country_id', $request->country_id)
                    ->whereRaw('assigned_roles.role_id = 5')
                    ->count();
        }
        return [
            'country' => $country,
            'amount' => $amount
        ];
    }

    public function find($id) {
        return User::find($id);
    }

    public function getAmountOfRescuers() {
        $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                ->whereIn('assigned_roles.role_id', [2, 3, 4])
                ->count();
        return $amount;
    }

    public function getRescuerAmount($request) {
        $country = City::where('id', $request->area_id)->value('name');
        if (!empty($request->state_id) && !empty($request->area_id)) {
            if ($request->rescuertype !== 'All') {
                $type=RescuerType::where('id',$request->rescuertype)->value('type');
                $role_id = \DB::table('roles')->where('name', $type)->value('id');
                $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                        ->where('users.area_id', $role_id)
                        ->where('assigned_roles.role_id', $request->rescuertype)
                        ->count();
            } else {
                $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                        ->where('users.area_id', $request->area_id)
                        ->whereIn('assigned_roles.role_id', [2, 3, 4])
                        ->count();
            }
        } else if (!empty($request->country_id)) {
            $country = Country::where('id', $request->country_id)->value('name');
            if ($request->rescuertype !== 'All') {
                $type=RescuerType::where('id',$request->rescuertype)->value('type');
                $role_id = \DB::table('roles')->where('name', $type)->value('id');
                $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                        ->where('users.country_id', $request->country_id)
                        ->where('assigned_roles.role_id', $role_id)
                        ->count();
            } else {
                $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                        ->where('users.country_id', $request->country_id)
                        ->whereIn('assigned_roles.role_id', [2, 3, 4])
                        ->count();
            }
        }
        return [
            'country' => $country,
            'amount' => $amount
        ];
    }

}
