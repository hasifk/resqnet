<?php

namespace App\Repositories\Backend\RescueOperation;

use App\Models\Access\Role\Role;
use App\Models\Access\User\User;
use App\Models\RescueOperation\ActiveRescuer;
use App\Models\RescueOperation\Location;
use App\Models\RescueOperation\Operation;
use App\Models\Rescuer\RescuerType;
use App\Models\UserGroups\Member;
use App\Models\UserGroups\UserGroup;
use App\Models\Access\EmergencyContact\EmergencyContact;
use App\Repositories\Backend\UserGroups\UserGroupsRepositoryContract;
use Illuminate\Http\Request;
use App\Models\Access\Payment\Payment;
use Auth;
use Storage;
use Carbon\Carbon;

class EloquentRescueOperationRepository {

    private $groups;

    public function __construct(UserGroupsRepositoryContract $groups) {

        $this->groups = $groups;
    }

    public function findActiveRescuers($result) {
        $type = RescuerType::where('id', $result->type)->value('type');
        if (!empty($type)) {
            $role = Role::where('name', $type)->value('id');
            if (!empty($role)) {
                $userid = $result->user_id;
                $userloc = $this->findUser($userid); //app user id
                if (!empty($userloc)) {
                    $actives = $this->activeUsers(); //getting all active users
                    $rescuers = array();
                    $f = 0;
                    if (!empty($userloc) && !empty($userloc->lat) && !empty($userloc->lng)) {
                        if (!empty($payment = Payment::where('user_id', $userid)->orderBy('id', 'desc')->first())) {
                            if (strtotime($payment->subscription_ends_at) >= strtotime(date('d-m-Y'))) {
                                $f = 1;
//                    $locations[$userid]['lat'] = $userloc->lat;
//                    $locations[$userid]['long'] = $userloc->lng;
//                    $locations[$userid]['addr'] = $userloc->address;
                                $locations[$userid]['lat'] = $result->lat;
                                $locations[$userid]['long'] = $result->lng;
                                $locations[$userid]['addr'] = $result->address;
                                foreach ($actives as $active) {
                                    if ($active->role_id == $role) {
                                        if (!empty($active->lat) && !empty($active->lng)) {
                                            if ($active->country_id == $userloc->country_id) {
                                                if (!empty($active->app_id) && !empty($active->device_type)):
                                                    $locations[$active->id]['lat'] = $active->lat;
                                                    $locations[$active->id]['long'] = $active->lng;
                                                    $locations[$active->id]['addr'] = $active->address;
                                                    $rescuers[] = $active->id;
                                                    //$distances[$active->id]=$this->distanceCalculation($userloc->lat, $userloc->lng, $active->lat, $active->lng);
                                                    $app_id['app_id'][$active->id] = $active->app_id;
                                                    $app_id['device_type'][$active->id] = $active->device_type;
                                                endif;
                                            }
                                        }
                                    }
                                }
                                if (!empty($contacts = $this->emergencyContacts($userid)))
                                    $appids = $this->membershipChecking($contacts, $rescuers);
                                if (!empty($userloc->emergency_groups)) {
                                    $group_ids = json_decode($userloc->emergency_groups);
                                    $gp = array();
                                    foreach ($group_ids as $gpid) {
                                        $group_user = Member::where('group_id', $gpid)->get();
                                        if (!empty($group_user)) {
                                            foreach ($group_user as $value) {
                                                if ($value->user_id != $userid) {
                                                    //  if (!in_array($value->user_id, $rescuers)) { 
                                                    if (!empty($appids)) {
                                                        if (!in_array($value->user_id, $gp)) {
                                                            if (!in_array($value->user_id, $appids[1])) {
                                                                $user = User::find($value->user_id);
                                                                if (!empty($user)) {
                                                                    if (!empty($user->app_id) && !empty($user->device_type)) {
                                                                        $groups[0]['app_id'][$value->user_id] = $user->app_id;
                                                                        $groups[0]['device_type'][$value->user_id] = $user->device_type;
                                                                        if (!empty($groups[1][$gpid]))
                                                                            $groups[1][$gpid] = $groups[1][$gpid] . ',' . $user->id;
                                                                        else
                                                                            $groups[1][$gpid] = $user->id;
                                                                        $gp[] = $user->id;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        if (!in_array($value->user_id, $gp)) {
                                                            $user = User::find($value->user_id);
                                                            if (!empty($user)) {
                                                                if (!empty($user->app_id) && !empty($user->device_type)) {
                                                                    $groups[0]['app_id'][$value->user_id] = $user->app_id;
                                                                    $groups[0]['device_type'][$value->user_id] = $user->device_type;
                                                                    if (!empty($groups[1][$gpid]))
                                                                        $groups[1][$gpid] = $groups[1][$gpid] . ',' . $user->id;
                                                                    else
                                                                        $groups[1][$gpid] = $user->id;
                                                                    $gp[] = $user->id;
                                                                }
                                                            }
                                                        }
                                                    }

                                                    //  }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else if ((!empty($userloc->per_lat) && !empty($userloc->per_lng))) {
                            if ($this->distanceCalculation($userloc->lat, $userloc->lng, $userloc->per_lat, $userloc->per_lng) <= 30) {
                                $f = 1;
                                $locations[$userid]['lat'] = $userloc->lat;
                                $locations[$userid]['long'] = $userloc->lng;
                                $locations[$userid]['addr'] = $userloc->address;
                                foreach ($actives as $active) {
                                    if ($active->role_id == $role) {
                                        if (!empty($active->lat) && !empty($active->lng)) {
                                            // $userdetails[] = $this->distanceCalculation($userloc->lat, $userloc->lng, $active->lat, $active->lng);
                                            if ($this->distanceCalculation($userloc->lat, $userloc->lng, $active->lat, $active->lng) <= 5) {
                                                if (!empty($active->app_id) && !empty($active->device_type)):
                                                    $locations[$active->id]['lat'] = $active->lat;
                                                    $locations[$active->id]['long'] = $active->lng;
                                                    $locations[$active->id]['addr'] = $active->address;
                                                    $rescuers[] = $active->id;
                                                    // $distances[$active->id]=$this->distanceCalculation($userloc->lat, $userloc->lng, $active->lat, $active->lng);
                                                    $app_id['app_id'][$active->id] = $active->app_id;
                                                    $app_id['device_type'][$active->id] = $active->device_type;
                                                endif;
                                            }
                                        }
                                    }
                                }
                            } else
                                $userdetails['result'] = "Please Upgrade to Paid Version";
                        }
                    } else
                        $userdetails['result'] = "Please Enable Location services";
                    if ($f == 1) {
                        //$rescuee = User::find($userid);
                        $message['message'] = "The User " . $userloc->firstname . " " . $userloc->lastname . " Requested Emergency Support(" . $result->emergency_type . ")";
                        sort($rescuers);
                        $obj = new ActiveRescuer;
                        $obj->rescuee_id = $userid;
                        $obj->role_id = $role;
                        $obj->rescuers_ids = !empty($rescuers) ? json_encode($rescuers) : '';
                        $obj->emergency_type = $result->emergency_type;
                        $obj->emergency_ids = !empty($appids) ? json_encode($appids[1]) : '';
                        $obj->emergency_groups = !empty($groups) ? json_encode($groups[1]) : '';
                        $obj->locations = json_encode($locations);
                        $obj->save();
                        $message['id'] = $obj->id;
                        // $userdetails[]=$app_id;
                        //return $userdetails;
                        if (!empty($rescuers)) {
                            $message['to'] = "Rescuer";
                            $this->notification($app_id, $message);
                            $userdetails['result'] = 'SUCCESS';
                            $userdetails['panicid'] = $obj->id;
                        } else
                            $userdetails['result'] = "There seems to be no resquers available within your radius";
                        if (!empty($appids)) {
                            $message['to'] = "Emergency";
                            $this->notification($appids[0], $message);
                        }
                        if (!empty($groups)) {
                            if (!empty($userloc->lat))
                                $addr = $userloc->address;
                            else
                                $addr = "Location Not available, Please Use Map";
                            $message['message'] = $userloc->firstname . " " . $userloc->lastname . " Sent a " . $result->emergency_type . " Panic Signal <br> Location <br> " . $addr;
                            $message['to'] = "EmergencyGroup";
                            $this->notification($groups[0], $message);
                        }
                    }
                } else
                    $userdetails = "Opps Error..Not valid User";
            } else
                $userdetails = "Opps Error..Not valid Role";
        } else
            $userdetails = "Opps Error..Not valid Type";
        return $userdetails;
    }

    public function rescueeOperationCancel($request) {
        $obj = ActiveRescuer::find($request->panicid);
        $obj->status = 0;
        $obj->save();
    }

    public function notification($app_id, $message) {

        foreach ($app_id['device_type'] as $key => $device) {
            if ($device == 'Android') {
                $android_ids[] = $app_id['app_id'][$key];
            } else {
                $ios_ids[] = $app_id['app_id'][$key];
            }
        }

        if (!empty($android_ids) && count($android_ids) > 0) {
            // API access key from Google API's Console
            if (!defined('API_ACCESS_KEY'))
                define('API_ACCESS_KEY', 'AIzaSyD0IORcVqQd4l9lfPTwfuSiThQeB7jj2YQ');
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
                'registration_ids' => $android_ids,
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
// Close connection
            curl_close($ch);
        } else if (!empty($ios_ids) && count($ios_ids) > 0) {
            // Provide the Host Information.
            $tHost = 'gateway.sandbox.push.apple.com';
            //$tHost = 'gateway.push.apple.com';
            $tPort = 2195;
// Provide the Certificate and Key Data.
            $tCert = base_path('public/') . 'pushcert.pem';

// Provide the Private Key Passphrase (alternatively you can keep this secrete
// and enter the key manually on the terminal -> remove relevant line from code).
// Replace XXXXX with your Passphrase
            $tPassphrase = 'SilverBloom1978';
// Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).
// Replace this token with the token of the iOS device that is to receive the notification.
//$tToken = 'b3d7a96d5bfc73f96d5bfc73f96d5bfc73f7a06c3b0101296d5bfc73f38311b4';
            $tToken = $ios_ids;
//0a32cbcc8464ec05ac3389429813119b6febca1cd567939b2f54892cd1dcb134
// The message that is to appear on the dialog.
            $tAlert = $message['message'];
// The Badge Number for the Application Icon (integer >=0).
            $tBadge = 8;
// Audible Notification Option.
            $tSound = 'default';
// The content that is returned by the LiveCode "pushNotificationReceived" message.
            $tPayload = 'APNS Message Handled by LiveCode';
// Create the message content that is to be sent to the device.
            $tBody['aps'] = array(
                'alert' => $tAlert,
                'badge' => $tBadge,
                'sound' => $tSound,
                'panicid' => $message['id'],
                'notification_type' => $message['to']
            );
            $tBody ['payload'] = $tPayload;

            // return $tBody;
// Encode the body to JSON.
            $tBody = json_encode($tBody);
// Create the Socket Stream.
            $tContext = stream_context_create();
            stream_context_set_option($tContext, 'ssl', 'local_cert', $tCert);
// Remove this line if you would like to enter the Private Key Passphrase manually.
            stream_context_set_option($tContext, 'ssl', 'passphrase', $tPassphrase);
// Open the Connection to the APNS Server.
            $tSocket = stream_socket_client('ssl://' . $tHost . ':' . $tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $tContext);
// Check if we were able to open a socket.
            if (!$tSocket)
                exit("APNS Connection Failed: $error $errstr" . PHP_EOL);
            foreach ($tToken as $token) {
// Build the Binary Notification.
                $tMsg = chr(0) . chr(0) . chr(32) . pack('H*', $token) . pack('n', strlen($tBody)) . $tBody;

                // Ensure that blocking is disabled
                stream_set_blocking($tSocket, 0);
                //stream_set_blocking($tSocket, 0);
// Send the Notification to the Server.
                $tResult = fwrite($tSocket, $tMsg, strlen($tMsg));
            }
            // $tResult = fwrite($tSocket, $tMsg);
//            if ($tResult)
//                return 'Delivered Message to APNS' . PHP_EOL;
//            else
//                return 'Could not Deliver Message to APNS' . PHP_EOL;
            //Close the Connection to the Server.
            fclose($tSocket);
        }
    }

    public function emergencyContacts($id) {
        $emergency = EmergencyContact::where('user_id', $id)->first();
        return !empty($emergency) ? $emergency->toArray() : $emergency;
    }

    public function membershipChecking($contacts, $rescuers) {
        $app_id = array();
        for ($i = 1; $i < 4; $i++) {
            if (!empty($contacts['emergency' . $i])) {
                $user = User::where('membership_no', $contacts['emergency' . $i])->first();
                if (!empty($user)) {
                    if (!in_array($user->id, $rescuers)) {
                        if (!empty($user->app_id) && !empty($user->device_type)):
                            $app_id[0]['app_id'][] = $user->app_id;
                            $app_id[0]['device_type'][] = $user->device_type;
                            $app_id[1][] = $user->id;
                        endif;
                    }
                }
            }
        }
        return $app_id;
    }

//for getting active users
    public function rescuerOperationDetails($active_rescuers_id) {
        $details = ActiveRescuer::join('users', 'activerescuers.rescuee_id', '=', 'users.id')
                        //->join('locations', 'activerescuers.rescuee_id', '=', 'locations.user_id')
                        ->select('activerescuers.id', 'activerescuers.emergency_type', 'activerescuers.rescuee_id', 'activerescuers.locations', 'users.firstname', 'users.lastname', 'users.phone', 'users.email', 'users.current_medical_conditions', 'users.prior_medical_conditions', 'users.allergies')
                        ->where('activerescuers.id', $active_rescuers_id)
                        ->first()->toArray();

        return $details;
    }

//for getting all active users
    public function rescuerOperationDetailsAll($rescuers_id) {
        $details = Operation::join('users', 'operations.rescuer_id', '=', 'users.id')
                ->join('activerescuers', 'operations.active_rescuers_id', '=', 'activerescuers.id')
                ->select('activerescuers.id', 'activerescuers.emergency_type', 'activerescuers.rescuee_id', 'activerescuers.locations', 'users.firstname', 'users.lastname', 'users.phone', 'users.email', 'users.current_medical_conditions', 'users.prior_medical_conditions', 'users.allergies')
                ->where('operations.rescuer_id', $rescuers_id->user_id)
                ->orderBy('operations.id', 'desc')
                ->paginate(20);

        return $details;
    }

    public function activeUsers() {
        return User::where('online_status', 1)->get();
    }

    public function ActiveRescuer($id) {
        return ActiveRescuer::find($id);
    }

    public function ActiveRescuers($id) {
        return ActiveRescuer::whereIn('id', $id)->orderBy('id', 'desc');
    }

    public function ActiveRescuerAll() {
        return ActiveRescuer::orderBy('id', 'desc')->get();
    }

    public function rescuerRole($id) {
        return ActiveRescuer::where('role_id', $id)->orderBy('id', 'desc')->get();
    }

    public function ActiveRescuerPaginate() {
        return ActiveRescuer::orderBy('id', 'desc')->paginate(20);
    }

    public function showLocation($userid) {
        return User::where('id', $userid)->where('online_status', 1)->first();
    }

    public function findOperations($activeid) {
        return Operation::where('active_rescuers_id', $activeid)->get();
    }

    public function findOperation($activeid) {
        return Operation::where('active_rescuers_id', $activeid)->first();
    }

    public function rescuersResponse($request) {
        $status = ActiveRescuer::find($request->active_rescuers_id);
        if (!empty($status) && $status->status == 1) {
            $operation = $this->findOperation($request->active_rescuers_id);
            if (empty($operation)):
                $obj = new Operation;
                $obj->active_rescuers_id = $request->active_rescuers_id;
                $obj->rescuer_id = $request->rescuer_id;
                $obj->save();
                $rescuee_id = $this->ActiveRescuer($request->active_rescuers_id);
                $user = User::find($request->rescuer_id);
                $message['message'] = $user->firstname . " " . $user->lastname . " is responding to your emergency. Help is on the way";
                $message['id'] = $obj->id;
                $message['to'] = "User";
                $user = User::find($rescuee_id->rescuee_id);
                $app_id['app_id'][] = $user->app_id;
                $app_id['device_type'][] = $user->device_type;
                $this->notification($app_id, $message);
                return $request->active_rescuers_id;
            else:
                // $user = User::find($request->rescuer_id);
//                $message['message'] = "Another Rescuer Accepted this request";
//                $message['id'] = $request->active_rescuers_id;
//                $message['to'] = "Rescuer";
//                $app_id['app_id'][] = $user->app_id;
//                $app_id['device_type'][] = $user->device_type;
//                $this->notification($app_id, $message);
                //return $request->active_rescuers_id;
                return "Another Rescuer Accepted this request";
            endif;
        }
        else if (empty($status)) {
            return 'Error';
        } else {
//            $user = User::find($request->rescuer_id);
//            $message['message'] = "This Request has been Cancelled by the User";
//            $message['id'] = $request->active_rescuers_id;
//            $message['to'] = "Rescuer";
//            $app_id['app_id'][] = $user->app_id;
//            $app_id['device_type'][] = $user->device_type;
//            $this->notification($app_id, $message);
            //return $request->active_rescuers_id;
            return "This Request has been Cancelled by the User";
        }
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
        return User::find($userid);
    }

    public function rescuerLocationUpdates($request) {
        $obj = User::find($request->user_id);
        $obj->lat = $request->lat;
        $obj->lng = $request->long;
        $obj->address = $request->address;
        $obj->online_status = 1;
        $obj->save();
        return $obj;
    }

    public function rescuerNotifications($request) {
        $user = User::find($request->user_id);
        $ids = array();
        if (!empty($rescuers = $this->rescuerRole($user->role_id))) {
            foreach ($rescuers as $rescuer) {
                if (!empty($rescuer->rescuers_ids)) {
                    $rescuer_ids = json_decode($rescuer->rescuers_ids);
                    if (in_array($request->user_id, $rescuer_ids)) {
                        $ids[] = $rescuer->id;
                    }
                }
            }
        }
        $lists = $this->ActiveRescuers($ids)->paginate(20);
        foreach ($lists as $key => $list) {
            $user = User::find($list->rescuee_id);
            $lists[$key]['name'] = $user->firstname . ' ' . $user->lastname;
        }
        return $lists;
    }

    public function listsOfRescuer($id) {
        $rescuers = $this->ActiveRescuer($id);
        if (!empty($rescuers)) {
            $res1 = $res2 = $res3 = $res4 = array();
            $rescuers['rescuee_details'] = User::find($rescuers->rescuee_id);
            if (!empty($rescuers->rescuers_ids)):
                $resccuer_id = json_decode($rescuers->rescuers_ids);
                foreach ($resccuer_id as $resid)
                    $res1[] = User::find($resid);
            endif;
            $rescuers['rescuers_details'] = $res1;
            if (!empty($rescuers->emergency_ids)):
                $emergency_id = json_decode($rescuers->emergency_ids);
                foreach ($emergency_id as $resid)
                    $res2[] = User::find($resid);
            endif;
            $rescuers['emergency_details'] = $res2;
            if (!empty($rescuers->emergency_groups)):
                $emergency_groups = json_decode($rescuers->emergency_groups);
                foreach ($emergency_groups as $k => $gp_user_id) {
                    $gp_user_id = explode(",", $gp_user_id);
                    $res3[] = $this->groups->userGroup($k);
                    $res4[$k] = $this->groups->userGroupdetails($k, $gp_user_id);
                }
            endif;

            $rescuers['emergency_groups'] = $res3;
            $rescuers['group_details'] = $res4;
            if (!empty($operation = Operation::where('active_rescuers_id', $rescuers->id)->first())) {
                $rescuers['tagged'] = User::find($operation->rescuer_id);
                $activetime = strtotime($rescuers->created_at);
                $operationtime = strtotime($operation->created_at);
                if (!empty($operation->finished_at)):
                    $finishedtime = strtotime($operation->finished_at);
                    $tot_sec = round(abs($finishedtime - $operationtime));
                    $rescuers['finished'] = $this->timeCalculator($tot_sec);
                endif;
                $tot_sec = round(abs($operationtime - $activetime));
                $rescuers['rescuerresponse'] = $this->timeCalculator($tot_sec);
            }
        }
        return $rescuers;
    }

    public function listsOfRescuers() {
        $rescuers = $this->ActiveRescuerPaginate();
        if (!empty($rescuers)) {
            foreach ($rescuers as $key => $active) {
                $rescuers[$key]['rescuee_details'] = User::find($active->rescuee_id);
                if (!empty($operation = Operation::where('active_rescuers_id', $active->id)->first())) {
                    $rescuers[$key]['tagged'] = User::find($operation->rescuer_id);
                    $activetime = strtotime($active->created_at);
                    $operationtime = strtotime($operation->created_at);
                    if (!empty($operation->finished_at)):
                        $finishedtime = strtotime($operation->finished_at);
                        $tot_sec = round(abs($finishedtime - $operationtime));
                        $rescuers[$key]['finished'] = $this->timeCalculator($tot_sec);
                    endif;
                    $tot_sec = round(abs($operationtime - $activetime));
                    $rescuers[$key]['rescuerresponse'] = $this->timeCalculator($tot_sec);
                }
            }
        }
        return $rescuers;
    }

    public function rescuersLists($panicids) {

        $rescuers = array();

        if (!empty($panicids)) {
            $rescuers = $this->ActiveRescuers($panicids)->paginate(20);
            foreach ($rescuers as $key => $active) {
                $rescuers[$key]['rescuee_details'] = User::find($active->rescuee_id);
                $operation = Operation::where('active_rescuers_id', $active->id)->first();
                if (!empty($operation)) {
                    $rescuers[$key]['tagged'] = User::find($operation->rescuer_id);
                    $activetime = strtotime($active->created_at);
                    $operationtime = strtotime($operation->created_at);
                    if (!empty($operation->finished_at)):
                        $finishedtime = strtotime($operation->finished_at);
                        $tot_sec = round(abs($finishedtime - $operationtime));
                        $rescuers[$key]['finished'] = $this->timeCalculator($tot_sec);
                    endif;
                    $tot_sec = round(abs($operationtime - $activetime));
                    $rescuers[$key]['rescuerresponse'] = $this->timeCalculator($tot_sec);
                }
            }
        } else
            $rescuers = $this->ActiveRescuers([0])->paginate(10);
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

    public function operationFinishing($request) {
        $operation = Operation::find($request->operation_id);
        if(!empty($operation)):
        $operation->finished_at = date("Y-m-d h:i:s");
        $operation->save();
        endif;
    }

}
