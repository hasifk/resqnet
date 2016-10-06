<?php

namespace App\Repositories\Backend\Statistics;

use App\Models\Access\User\User;
use App\Models\Countries\City;
use App\Models\Countries\Country;
use App\Models\Rescuer\RescuerType;
use App\Models\Newsfeed\Newsfeed;
use App\Models\Access\Role\Role;
use App\Models\RescueOperation\ActiveRescuer;
use App\Repositories\Backend\RescueOperation\EloquentRescueOperationRepository;
use Auth;
use Storage;

class EloquentStatisticsRepository implements StatisticsRepositoryContract {

    private $rescueOperationRepository;

    public function __construct(EloquentRescueOperationRepository $rescueOperationRepository) {
        $this->rescueOperationRepository = $rescueOperationRepository;
    }

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
            $amount = Newsfeed::where('areaid', $request->area_id)->where('newsfeed_type',$request->rescur)->count();            
        } else if (!empty($request->country_id)) {
            $country = Country::where('id', $request->country_id)->value('name');
                $amount = Newsfeed::where('countryid', $request->country_id)->where('newsfeed_type',$request->rescur)->count();
        }
        return [
            'country' => $country,
            'amount' => $amount
        ];
    }

   public function area($value) {
        return ActiveRescuer::join('users', 'activerescuers.rescuee_id', '=', 'users.id')->where('users.area_id', $value)
                        ->select('activerescuers.rescuers_ids', 'activerescuers.id','activerescuers.role_id');
    }

    public function country($value) {
        return ActiveRescuer::join('users', 'activerescuers.rescuee_id', '=', 'users.id')->where('users.country_id', $value)
                        ->select('activerescuers.rescuers_ids', 'activerescuers.id','activerescuers.role_id')->orderBy('activerescuers.id', 'desc');
    }

    public function getPanicSignalAmount($request) {

        $country = Country::where('id',$request->country_id)->value('name');
        if ($request->category != "All") {
            if (!empty($request->date)):
                if (!empty($request->state_id) && !empty($request->area_id)) {
                    $area = City::where('id',$request->area_id)->value('name');
                    $actives = $this->area($request->area_id)->where('activerescuers.emergency_type', $request->category)->where(\DB::raw("DATE(created_at) = '" . $request->date . "'"))->orderBy('activerescuers.id', 'desc')->get();
                } else if (!empty($request->country_id)) {
                    $actives = $this->country($request->country_id)->where('activerescuers.emergency_type', $request->category)->where(\DB::raw("DATE(created_at) = '" . $request->date . "'"))->orderBy('activerescuers.id', 'desc')->get();
                } else
                    $actives = ActiveRescuer::where('emergency_type', $request->category)->where(\DB::raw("DATE(created_at) = '" . $request->date . "'"))->orderBy('activerescuers.id', 'desc')->get();
            else:
                if (!empty($request->state_id) && !empty($request->area_id)) {
                    $area = City::where('id',$request->area_id)->value('name');
                    $actives = $this->area($request->area_id)->where('activerescuers.emergency_type', $request->category)->get();
                } else if (!empty($request->country_id)) {
                    $actives = $this->country($request->country_id)->where('activerescuers.emergency_type', $request->category)->get();
                } else
                    $actives = ActiveRescuer::where('emergency_type', $request->category)->orderBy('activerescuers.id', 'desc')->get();
            endif;
        }
        else {
            if (!empty($request->date)):
                if (!empty($request->state_id) && !empty($request->area_id)) {
                    $area = City::where('id',$request->area_id)->value('name');
                    $actives = $this->area($request->area_id)->where(\DB::raw("DATE(created_at) = '" . $request->date . "'"))->orderBy('activerescuers.id', 'desc')->get();
                } else if (!empty($request->country_id)) {
                    $actives = $this->country($request->country_id)->where(\DB::raw("DATE(created_at) = '" . $request->date . "'"))->orderBy('activerescuers.id', 'desc')->get();
                } else
                    $actives = ActiveRescuer::where(\DB::raw("DATE(created_at) = '" . $request->date . "'"))->orderBy('activerescuers.id', 'desc')->get();
            else:
                if (!empty($request->state_id) && !empty($request->area_id)) {
                    $area = City::where('id',$request->area_id)->value('name');
                    $actives = $this->area($request->area_id)->orderBy('activerescuers.id', 'desc')->get();
                } else if (!empty($request->country_id)) {
                    $actives = $this->country($request->country_id)->get();
                } else
                    $actives = $this->rescueOperationRepository->ActiveRescuerAll();
            endif;
        }

        if ($request->rescur != "All")
            $role[] = Role::where('name', $request->rescur)->value('id');
        else
            $role = array(2, 3, 4);
        $f = 0;
        $lists=array();
        if (!empty($actives)) {
            foreach ($actives as $active) {
                if (in_array($active->role_id, $role)) {
                    $f++;
                    $lists[] = $active->id;
                }
            }
        }
        return [
            'country' => $country,
            'area' => !empty($area)?$area:'---',
            'amount' => $f,
            'lists' => $lists
        ];
    }

}
