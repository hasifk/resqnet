<?php

namespace App\Repositories\Backend\Statistics;


use App\Models\Access\User\User;

use App\Models\Countries\City;
use App\Models\Countries\Country;
use Auth;
use Storage;

class EloquentStatisticsRepository implements StatisticsRepositoryContract
{


    public function getUsersbyCountry($request)
    {
        $country=Country::where('id',$request->country_id)->value('name');
        $amount= User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
            ->where('users.country_id', $request->country_id )
            ->whereRaw('assigned_roles.role_id = 5')
            ->count();
        return [
            'country'              => $country,
            'amount'         => $amount
        ];
    }
    public function getUsersbyArea($request)
    {
        $country=City::where('id',$request->area_id)->value('name');
        $amount= User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
            ->where('users.area_id', $request->area_id)
            ->whereRaw('assigned_roles.role_id = 5')
            ->count();
        return [
            'area'              => $country,
            'amount'         => $amount
        ];
    }



    public function find($id)
    {
        return User::find($id);
    }



}
