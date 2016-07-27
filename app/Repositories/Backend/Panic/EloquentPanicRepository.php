<?php

namespace App\Repositories\Backend\Panic;

use App\Models\Panic\Location;
use App\Models\Access\User;
use Illuminate\Http\Request;
use Auth;
use Storage;

class EloquentPanicRepository {
    
    
        public function findResquers($request) {  //find resquers within 5 KM
            $result = json_decode(file_get_contents('php://input'));
            $role=$result->role;
            $userloc=$this->showlocation($userid);//app user id
            $actives=$this->activeUsers(); //getting all active users
            $users=array();
            foreach($actives as $active)
            {
                $user=$active->user_id;
                if(User::find($user)->roles()->id==$role)
                {
                if($this->distanceCalculation($userloc->lat,$userloc->long,$active->lat,$active->long)<=5)
                {
                    $users[]=$user;
                }
                }
            }
    }
    
    //for getting all active users
    
    public function activeUsers(){ 
       return Location::where('active',1)->get();
    }
    public function showlocation($userid)
    {
        return Location::where('user_id',$userid)->first();
    }
    
    public function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
	// Calculate the distance in degrees
	$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
 
	// Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
	$distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
			
	return round($distance, $decimals);
}

}
