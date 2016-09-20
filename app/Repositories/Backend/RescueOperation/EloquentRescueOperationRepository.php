<?php

namespace App\Repositories\Backend\RescueOperation;

use App\Models\Access\Role\Role;
use App\Models\Access\User\User;
use App\Models\RescueOperation\ActiveRescuer;
use App\Models\RescueOperation\Location;
use App\Models\RescueOperation\Operation;
use App\Models\Rescuer\RescuerType;
use App\Models\Access\EmergencyContact\EmergencyContact;
use Illuminate\Http\Request;
use Auth;
use Storage;

class EloquentRescueOperationRepository {

    public function findActiveRescuers($result) {
        $type = RescuerType::where('id', $result->type)->value('type');
        $role = Role::where('name', $type)->value('id');
        $userid = $result->user_id;
        $userloc = $this->showLocation($userid); //app user id
        $actives = $this->activeUsers(); //getting all active users
        foreach ($actives as $active) {
            $user = User::find($active->user_id);
            if ($user->role_id == $role) {
                // $rescuers[] = $active->user_id;
                if ($this->distanceCalculation($userloc->lat, $userloc->long, $active->lat, $active->long) <= 5) {

                    $rescuers[] = $active->user_id;
                    $app_id['app_id'][] = $user->app_id;
                    $app_id['device_type'][] = $user->device_type;
                }
            }
        }
        //$userdetails='';
        if (!empty($rescuers)):
            sort($rescuers);
            $obj = new ActiveRescuer;
            $obj->rescuee_id = $userid;
            $obj->rescuers_ids = json_encode($rescuers);
            $obj->emergency_type = $result->emergency_type;
            $obj->save();
            $rescuee = User::find($userid);
            $message['message'] = "The User " . $rescuee->firstname . " " . $rescuee->lastname . " Reqested an Emergency(" . $result->emergency_type . ")";
            $message['id'] = $obj->id;
            $message['to'] = "Rescuer";
            $this->notification($app_id, $message);
            if (!empty($contacts = $this->emergencyContacts($userid))) {
                if (!empty($contacts->emergency1))
                    $app_id = $this->membershipChecking($contacts->emergency1);
                if (!empty($contacts->emergency2))
                   $app_id= $this->membershipChecking($contacts->emergency2);
                if (!empty($contacts->emergency3))
                   $app_id= $this->membershipChecking($contacts->emergency3);
                $message['to'] = "Emergency";
                    $this->notification($app_id, $message);
            }
            $userdetails = 'SUCCESS';
        else:
            $userdetails = "No Rescuers available";
        endif;
        return $userdetails;
    }

    public function notification($app_id, $message) {
        // API access key from Google API's Console
        define('API_ACCESS_KEY', 'AIzaSyAk7I1q81uAHbXgxkVKcMr46bRpAtxC7wQ');
        foreach ($app_id['device_type'] as $key => $device) {
            // $ar[]=array($app_id['app_id'][$key]);
            if ($device == 'Android') {
                // prep the bundle

                $msg = array
                    (
                    'message' => $message['message'],
                    'title' => "Notification",
                    'subtitle' => 'This is a subtitle. subtitle',
                    'tickerText' => 'Ticker text here...Ticker text here...Ticker text here',
                    'vibrate' => 1,
                    'sound' => 1,
                    'largeIcon' => 'large_icon',
                    'smallIcon' => 'small_icon',
                    'panicid' => $message['id'],
                    'notification_type' => $message['to']
                );
                $fields = array
                    (
                    'registration_ids' => array($app_id['app_id'][$key]),
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
                //echo $result;
                // Close connection
                curl_close($ch);
            } else {
                
            }
        }
    }

    public function emergencyContacts($id) {
        return EmergencyContact::where('user_id', $id)->first();
    }

    public function membershipChecking($number) {
        $user = User::where('membership_no', $number)->first();
        if (!empty($user)) {
            $app_id['app_id'][] = $user->app_id;
            $app_id['device_type'][] = $user->device_type;
        }
        return $app_id;
    }

    //for getting all active users
    public function rescuerOperationDetails($active_rescuers_id) {
        $details = ActiveRescuer::join('users', 'activerescuers.rescuee_id', '=', 'users.id')
                        ->join('locations', 'activerescuers.rescuee_id', '=', 'locations.user_id')
                        ->select('activerescuers.id', 'activerescuers.emergency_type', 'users.firstname', 'users.lastname', 'users.phone', 'users.email', 'users.current_medical_conditions', 'users.prior_medical_conditions', 'users.allergies', 'locations.address', 'locations.lat', 'locations.long')
                        ->where('activerescuers.id', $active_rescuers_id)
                        ->get()->toArray();

        return $details;
    }

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

    public function findOperation($activeid) {
        return Operation::where('active_rescuers_id', $activeid)->first();
    }

    public function rescuersResponse($request) {
        $operation = $this->findOperation($request->active_rescuers_id);
        if (empty($operation)):
            $obj = new Operation;
            $obj->active_rescuers_id = $request->active_rescuers_id;
            $obj->rescuer_id = $request->rescuer_id;
            $obj->save();
            $rescuee_id = $this->ActiveRescuer($request->active_rescuers_id)->value('rescuee_id');
            $user = User::find($request->rescuer_id);
            $message['message'] = $user->firstname . " " . $user->lastname . " Accepted Your Request";
            $message['id'] = $request->active_rescuers_id;
            $message['to'] = "User";
            $user = User::find($rescuee_id);
        else:
            $user = User::find($request->rescuer_id);
            $message['message'] = "Another Rescuer Accepted this request";
            $message['id'] = $request->active_rescuers_id;
            $message['to'] = "Rescuer";
        endif;
        $app_id['app_id'][] = $user->app_id;
        $app_id['device_type'][] = $user->device_type;

        $this->notification($app_id, $message);
        return $request->active_rescuers_id;
        ;
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
        $obj->address = $request->address;
        $obj->status = 1;
        $obj->save();
        return $obj;
    }

    public function rescuersLists() {
        $rescuers = $this->ActiveRescuerPaginate();
        if (!empty($rescuers)) {
            foreach ($rescuers as $key => $active) {
                $res = array();
                $rescuers[$key]['rescuee_details'] = User::find($active->rescuee_id);
                if (!empty($active->rescuers_ids)):
                    $resccuer_id = json_decode($active->rescuers_ids);
                    foreach ($resccuer_id as $resid)
                        $res[] = User::find($resid);
                endif;
                $rescuers[$key]['rescuers_details'] = $res;
                $operation = Operation::where('active_rescuers_id', $active->id)->first();
                if (!empty($operation)) {
                    $rescuers[$key]['tagged'] = User::find($operation->rescuer_id);
                    $activetime = strtotime($active->created_at);
                    $operationtime = strtotime($operation->created_at);
                    if (!empty($operation->finished_at)):
                        $finishedtime = strtotime($operation->finished_at);
                        $tot_sec = round(abs($finishedtime - $operationtime));
                        $rescuers[$key]['rescuerresponse'] = $this->timeCalculator($tot_sec);
                    endif;
                    $tot_sec = round(abs($operationtime - $activetime));
                    $rescuers[$key]['panicresponse'] = $this->timeCalculator($tot_sec);
                }
            }
        }
        return $rescuers;
    }

    public function listsOfRescuers($panicids) {

        $rescuers = array();
        if (!empty($panicids)) {
            foreach ($panicids as $value)
                $rescuers[] = $this->ActiveRescuer($value);
            foreach ($rescuers as $key => $active) {
                $res = array();
                $rescuers[$key]['rescuee_details'] = User::find($active->rescuee_id);
                if (!empty($active->rescuers_ids)):
                    $resccuer_id = json_decode($active->rescuers_ids);
                    foreach ($resccuer_id as $resid)
                        $res[] = User::find($resid);
                endif;
                $rescuers[$key]['rescuers_details'] = $res;
                $operation = Operation::where('active_rescuers_id', $active->id)->first();
                if (!empty($operation)) {
                    $rescuers[$key]['tagged'] = User::find($operation->rescuer_id);
                    $activetime = strtotime($active->created_at);
                    $operationtime = strtotime($operation->created_at);
                    if (!empty($operation->finished_at)):
                        $finishedtime = strtotime($operation->finished_at);
                        $tot_sec = round(abs($finishedtime - $operationtime));
                        $rescuers[$key]['rescuerresponse'] = $this->timeCalculator($tot_sec);
                    endif;
                    $tot_sec = round(abs($operationtime - $activetime));
                    $rescuers[$key]['panicresponse'] = $this->timeCalculator($tot_sec);
                }
            }
        }
        return $rescuers;
    }

    public function timeCalculator($tot_sec) {
        $hours = floor($tot_sec / 3600);
        $minutes = floor(($tot_sec / 60) % 60);
        $seconds = $tot_sec % 60;
        if ($hours >= 24) {
            $days = floor($hours / 24);
            $hr = floor($hours % 24);
            $hours = $days . ' Days & ' . $hr;
        }
        return "$hours:$minutes:$seconds";
    }

}
