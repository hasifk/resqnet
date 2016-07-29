<?php

namespace App\Repositories\Backend\RescueOperation;

use App\Models\Access\User;
use App\Models\RescueOperation\ActiveRescuer;
use App\Models\RescueOperation\Location;
use App\Models\RescueOperation\Operation;
use App\Models\Rescuer\RescuerType;
use Illuminate\Http\Request;
use Auth;
use Storage;

class EloquentRescueOperationRepository {

    public function findActiveRescuers($request) {  //find resquers within 5 KM
        $result = json_decode(file_get_contents('php://input'));
        $role = $result->role;
        $userid = $result->userid;
        $userloc = $this->showLocation($userid); //app user id
        $actives = $this->activeUsers(); //getting all active users
        foreach ($actives as $active) {
            $user = $active->user_id;
            if (User::find($user)->roles()->id == $role) {
                if ($this->distanceCalculation($userloc->lat, $userloc->long, $active->lat, $active->long) <= 5) {
                    $rescuers[] = $user;
                }
            }
        }
        sort($rescuers);
        $obj = new ActiveRescuer;
        $obj->rescuee_id = $userid;
        $obj->rescuers_id = json_encode($rescuers);
        $obj->save();
    }

    //for getting all active users

    public function activeUsers() {
        return Location::where('status', 1)->get();
    }

    public function showLocation($userid) {
        return Location::where('user_id', $userid)->first();
    }

    public function rescuersResponce($request) {
        $obj = new Operation;
        $result = json_decode(file_get_contents('php://input'));
        $obj->active_rescuers_id = $result->active_rescuers_id;
        $obj->rescuee_id = $result->rescuee_id;
        $obj->rescuer_id = $result->rescuer_id;
        $obj->save();
    }

    public function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
        // Calculate the distance in degrees
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat)) * sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat)) * cos(deg2rad($point2_lat)) * cos(deg2rad($point1_long - $point2_long)))));

        // Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
        $distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)

        return round($distance, $decimals);
    }
    public function rescueeForm()
    {
       return RescuerType::select(['id', 'type'])->get();
    }
    

}
