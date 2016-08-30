<?php

namespace App\Repositories\Backend\RescueOperation;

use App\Models\Access\Role\Role;
use App\Models\Access\User\User;
use App\Models\RescueOperation\ActiveRescuer;
use App\Models\RescueOperation\Location;
use App\Models\RescueOperation\Operation;
use App\Models\Rescuer\RescuerType;
use Illuminate\Http\Request;
use Auth;
use Storage;

class EloquentRescueOperationRepository {

    public function findActiveRescuers($result) {
        $type = RescuerType::where('id', $result->type)->value('type');
        $role = Role::where('name', $type)->value('id');
        $userid = $result->userid;
        $userloc = $this->showLocation($userid); //app user id
        $actives = $this->activeUsers(); //getting all active users
        foreach ($actives as $active) {
            $user = User::find($active->user_id);
            if ($user->role_id == $role) {
                if ($this->distanceCalculation($userloc->lat, $userloc->long, $active->lat, $active->long) <= 5) {
                    $rescuers[] = $active->user_id;
                }
            }
        }

        sort($rescuers);
        $obj = new ActiveRescuer;
        $obj->rescuee_id = $userid;
        $obj->rescuers_ids = json_encode($rescuers);
        $obj->emergency_type = $result->emergency_type;
        $obj->save();
        $userdetails['rescuee'] = User::find($userid);
        $userdetails['rescuer'] = $rescuers;
        $userdetails['active_rescuers_id'] = $obj->id;
        return $userdetails;
    }

    //for getting all active users

    public function activeUsers() {
        return Location::where('status', 1)->get();
    }

    public function ActiveRescuer($id) {
        return ActiveRescuer::find($id);
    }

    public function ActiveRescuerAll() {
        return ActiveRescuer::orderBy('id','desc')->get();
    }

    public function showLocation($userid) {
        return Location::where('user_id', $userid)->where('status', 1)->first();
    }

    public function rescuersResponse($request) {
        $obj = new Operation;
        /* $result = json_decode(file_get_contents('php://input')); */
        $result = $request;
        $obj->active_rescuers_id = $result->active_rescuers_id;
        $obj->rescuee_id = $result->rescuee_id;
        $obj->rescuer_id = $result->rescuer_id;
        $obj->save();
        return $obj;
    }

    public function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
        // Calculate the distance in degrees
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat)) * sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat)) * cos(deg2rad($point2_lat)) * cos(deg2rad($point1_long - $point2_long)))));

        // Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
        $distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)

        return round($distance, $decimals);
    }

    public function rescueeForm() {
        return RescuerType::select(['id', 'type'])->get();
    }

    public function findUser($userid) {
        return Location::where('user_id', $userid)->first();
    }

    public function rescuerLocationUpdates($request) {
        $user = $this->findUser($request->user_id);
        if (!empty($user))
            $obj = $user;
        else
            $obj = new Location;
        $obj->user_id = $request->user_id;
        $obj->lat = $request->lat;
        $obj->long = $request->long;
        $obj->save();
        return $obj;
    }

    public function listsOfRescuers() {
//        $obj = new ActiveRescuer;
//        $obj->rescuee_id = 3;
//        $rescuers=array(1,2);
//        $obj->rescuers_ids = json_encode($rescuers);
//        $obj->save();
        
        $rescuers = $this->ActiveRescuerAll();
        $users=array();
        if(!empty($rescuers))
        {
            foreach ($rescuers as $active)
            {
                $users[$active->rescuee_id]=User::find($active->rescuee_id)->firstname;
                $resccuer_id=json_decode($active->rescuers_ids);
                foreach($resccuer_id as $resid)
                $users[$resid] = User::find($resid)->firstname;
            }
        }
        
        return $users;
    }

}
