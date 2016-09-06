<?php

namespace App\Repositories\Backend\Statistics;

use App\Models\Access\User\User;
use App\Models\Countries\City;
use App\Models\Countries\Country;
use App\Models\Rescuer\RescuerType;
use App\Models\Newsfeed\Newsfeed;
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
        if (!empty($request->state_id) && !empty($request->area_id)) {
            $country = City::where('id', $request->area_id)->value('name');
            if ($request->rescuertype !== 'All') {
                $type = RescuerType::where('id', $request->rescuertype)->value('type');
                $role_id = \DB::table('roles')->where('name', $type)->value('id');
                $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                        ->where('users.area_id', $role_id)
                        ->where('assigned_roles.role_id', $request->rescuertype)
                        ->count();
            } else {
                $type = '';
                $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                        ->where('users.area_id', $request->area_id)
                        ->whereIn('assigned_roles.role_id', [2, 3, 4])
                        ->count();
            }
        } else if (!empty($request->country_id)) {
            $country = Country::where('id', $request->country_id)->value('name');
            if ($request->rescuertype !== 'All') {
                $type = RescuerType::where('id', $request->rescuertype)->value('type');
                $role_id = \DB::table('roles')->where('name', $type)->value('id');
                $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                        ->where('users.country_id', $request->country_id)
                        ->where('assigned_roles.role_id', $role_id)
                        ->count();
            } else {
                $type = '';
                $amount = User::join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                        ->where('users.country_id', $request->country_id)
                        ->whereIn('assigned_roles.role_id', [2, 3, 4])
                        ->count();
            }
        }
        return [
            'country' => $country,
            'amount' => $amount,
            'type' => $type,
        ];
    }

    public function getAmountOfNewsfeeds() {
        $amount = Newsfeed::count();
        return $amount;
    }

    public function getNewsfeedAmount($request) {

        if (!empty($request->state_id) && !empty($request->area_id)) {
            $country = City::where('id', $request->area_id)->value('name');
            if ($request->rescur == "Rescuer")
                $amount = Newsfeed::where('resquer_areaid', $request->area_id)->count();
            else if ($request->rescur == "Rescuee")
                $amount = Newsfeed::where('user_areaid', $request->area_id)->count();
            else
                $amount = Newsfeed::where('resquer_areaid', $request->area_id)->orWhere('user_areaid', $request->area_id)->count();
        } else if (!empty($request->country_id)) {
            $country = Country::where('id', $request->country_id)->value('name');
            if ($request->rescur == "Rescuer")
                $amount = Newsfeed::where('resquer_countryid', $request->country_id)->count();
            else if ($request->rescur == "Rescuee")
                $amount = Newsfeed::where('user_countryid', $request->country_id)->count();
            else
                $amount = Newsfeed::where('resquer_countryid', $request->country_id)->orWhere('user_countryid', $request->country_id)->count();
        }
        return [
            'country' => $country,
            'amount' => $amount
        ];
    }

    public function getPanicSignalAmount($request) {
        if ($request->rescur != "All") {
                $type = RescuerType::where('id', $result->rescur)->value('type');
                $role = Role::where('name', $type)->value('id');
            } 
            if ($request->category != "All")
                $actives = ActiveRescuer::where('emergency_type', $request->category)->orderBy('id', 'desc')->get();
            else
           $actives=$this->rescueOperationRepository->ActiveRescuerAll();
           foreach($actives as $active)
           {
               if(!empty($request->date))
               {
                   
               }
           }
        if (!empty($request->state_id) && !empty($request->area_id)) {
                $amount = "join('user',resquer_areaid', $request->area_id)->orWhere('user_areaid', $request->area_id)->count()";
        } else if (!empty($request->country_id)) {
            $country = Country::where('id', $request->country_id)->value('name');
            if ($request->rescur == "Rescuer")
                $amount = Newsfeed::where('resquer_countryid', $request->country_id)->count();
            else if ($request->rescur == "Rescuee")
                $amount = Newsfeed::where('user_countryid', $request->country_id)->count();
            else
                $amount = Newsfeed::where('resquer_countryid', $request->country_id)->orWhere('user_countryid', $request->country_id)->count();
        }
        return [
            'country' => $country,
            'amount' => $amount
        ];
    }

}
