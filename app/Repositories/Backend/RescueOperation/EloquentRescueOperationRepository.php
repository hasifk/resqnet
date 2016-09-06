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
                /* if ($this->distanceCalculation($userloc->lat, $userloc->long, $active->lat, $active->long) <= 5) */
                if ($this->distanceCalculation($userloc->lat, $userloc->long, $active->lat, $active->long)) {
                    $rescuers[] = $active->user_id;
                    if ($user->device_type == 'Android')
                        $app_id[] = $user->app_id;
                }
            }
        }
        $userdetails = '';
        if (!empty($rescuers)):
            sort($rescuers);
            $obj = new ActiveRescuer;
            $obj->rescuee_id = $userid;
            $obj->rescuers_ids = json_encode($rescuers);
            $obj->emergency_type = $result->emergency_type;
            $obj->save();
            $rescuee = User::find($userid);
           // $this->notification($app_id);
           // $userdetails['rescuee'] = $rescuee->toArray();
           // $userdetails['rescuer'] = $rescuers;
            $userdetails['active_rescuers_id'] = $obj->id;
        else:
            $userdetails['status'] = "No Rescuers available";
        endif;
        return $userdetails;
    }

    public function notification($app_id) {
        if (!empty($app_id) && count($app_id) > 0) {

// API access key from Google API's Console
            define('API_ACCESS_KEY', 'AIzaSyAk7I1q81uAHbXgxkVKcMr46bRpAtxC7wQ');

// prep the bundle

            $msg = array
                (
                'message' => 'Gogul Moonchiii',
                'title' => "Notification",
                'subtitle' => 'This is a subtitle. subtitle',
                'tickerText' => 'Ticker text here...Ticker text here...Ticker text here',
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon'
            );
            $fields = array
                (
                'registration_ids' => $app_id,
                'data' => $msg
            );

            $headers = array
                (
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            echo $result;
// Close connection
            curl_close($ch);
        }
    }

    //for getting all active users

    public function activeUsers() {
        return Location::where('status', 1)->get();
    }

    public function ActiveRescuer($id) {
        return ActiveRescuer::find($id);
    }

    public function ActiveRescuerAll() {
        return ActiveRescuer::orderBy('id', 'desc')->get();
    }

    public function ActiveRescuerPaginate() {
        return ActiveRescuer::orderBy('id', 'desc')->paginate(10);
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
        $obj->status = 1;
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
        $users = array();
        if (!empty($rescuers)) {
            foreach ($rescuers as $active) {

                $users['active'][$active->rescuee_id] = User::find($active->rescuee_id);
                if (!empty($active->rescuers_ids)):
                    $resccuer_id = json_decode($active->rescuers_ids);
                    foreach ($resccuer_id as $resid)
                        $users['active'][$resid] = User::find($resid);
                endif;
                $operation = Operation::where('active_rescuers_id', $active->id)->first();
                if (!empty($operation)) {
                    $users['tagged'][$active->id] = User::find($operation->rescuer_id);
                    $activetime = strtotime($active->created_at);
                    $operationtime = strtotime($operation->created_at);
                    if (!empty($operation->finished_at)):
                        $finishedtime = strtotime($operation->finished_at);
                        $tot_sec = round(abs($finishedtime - $operationtime));
                        $users['rescuerresponse'][$active->id] = $this->timeCalculator($tot_sec);
                    endif;
                    $tot_sec = round(abs($operationtime - $activetime));
                    $users['panicresponse'][$active->id] = $this->timeCalculator($tot_sec);
                }
            }
        }
        return $users;
    }

    public function timeCalculator($tot_sec) {
        $hours = floor($tot_sec / 3600);
        $minutes = floor(($tot_sec / 60) % 60);
        $seconds = $tot_sec % 60;

        return "$hours:$minutes:$seconds";
    }

    public function taggedRescuer() {
        return Operation::join('activerescuers', 'activerescuers.id', '=', 'operations.active_rescuers_id')
                        ->join('user', 'user.id', '=', 'operations.rescuer_id')
                        ->select('operations.*', 'user.firstname', 'user.lastname')
                        ->get();
    }

}
