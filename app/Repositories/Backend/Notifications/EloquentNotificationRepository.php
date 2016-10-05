<?php

namespace App\Repositories\Backend\Notifications;

use App\Models\Notifications\Notification;
use App\Models\Notifications\NotificationCategory;
use App\Models\Access\User\User;
use App\Models\Countries\Country;
use App\Models\Countries\City;
use Auth;
use Event;


class EloquentNotificationRepository implements NotificationRepositoryContract {

    public function shows() {
        $userid = Auth::user()->id;
        $results= Notification::join('notifcategories','notifications.notif_cat','=','notifcategories.id')
                ->where('notifications.user_id',$userid)->orderBy('notifications.id', 'desc')->select('notifications.*','notifcategories.category')
                ->paginate(10);
        foreach($results as $key => $value)
        {
            if(!empty($value->country_id))
                $results[$key]['country']=Country::find($value->country_id)->value('name');
            if(!empty($value->area_id))
                $results[$key]['area']=City::find($value->area_id)->value('name');
        }
        return $results;
    }

    public function show($id) {

        $userid = Auth::user()->id;
        return Notification::where('user_id', $userid)->orderBy('id', 'desc')
                        ->paginate(10);
    }

    public function category() {
        return NotificationCategory::get();
    }

    public function save($request) {
        $userid = Auth::user()->id;
        $obj = new Notification;
        $obj->user_id = $userid;
        $obj->notif_cat = $request->notif_cat;
        $obj->country_id = (!empty($request->country_id)) ? $request->country_id : '';
        $obj->area_id = (!empty($request->area_id)) ? $request->area_id : '';
        $obj->notification = $request->notification;
        $obj->save();
        $message = $request->notification;
        if (!empty($request->country_id)) {
            if (!empty($request->area_id))
                $users = User::where('country_id', $request->country_id)->where('area_id', $request->area_id)->orderBy('id', 'desc')->get();
            else
                $users = User::where('country_id', $request->country_id)->orderBy('id', 'desc')->get();
        }
        else if (!empty($request->notif_cat == 2)) {
            $users = User::orderBy('id', 'desc')->get();
        }
        if (!empty($users)) {
            foreach ($users as $value) {
                if ($value->role_id != 1) {
                    $app_id['device_type'][] = $value->device_type;
                    $app_id['app_id'][] = $value->app_id;
                }
            }
        }
        
    $this->notification($app_id, $message);
    }

    public function notification($app_id, $message) {
        // API access key from Google API's Console
        // define('API_ACCESS_KEY', 'AIzaSyAk7I1q81uAHbXgxkVKcMr46bRpAtxC7wQ');
        foreach ($app_id['device_type'] as $key => $device) {
            // $ar[]=array($app_id['app_id'][$key]);
            if ($device == 'Android') {
                // prev the bundle
                
                $msg = array
                    (
                    'message' => $message,
                    'title' => "Notification",
                    'subtitle' => 'This is a subtitle. subtitle',
                    'tickerText' => 'Ticker text here...Ticker text here...Ticker text here',
                    'vibrate' => 1,
                    'sound' => 1,
                    'largeIcon' => 'large_icon',
                    'smallIcon' => 'small_icon',
//                    'panicid' => $message['id'],
//                    'notification_type' => $message['to']
                );
                $fields = array
                    (
                    'registration_ids' => array($app_id['app_id'][$key]),
                    'data' => $msg
                );
                $headers = array
                    (
                    'Authorization: key=' . 'AIzaSyAk7I1q81uAHbXgxkVKcMr46bRpAtxC7wQ',
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

    public function find($id) {
        return Notification::find($id);
    }

    public function filter($request) {
        $userid = Auth::user()->id;
        if (empty($request->country_id))
            return $this->shows();
        else if (!empty($request->state_id) && !empty($request->area_id)) {
            return Notification::where('user_id', $userid)->where('area_id', $request->area_id)->orderBy('id', 'desc')
                            ->paginate(10);
        } else {
            return Notification::where('user_id', $userid)->where('country_id', $request->country_id)->orderBy('id', 'desc')
                            ->paginate(10);
        }
    }

    public function NotificationDelete($request) {
        $ids = explode(",", $request->id);
        foreach ($ids as $value):
            Notification::where('id', $value)->delete();
        endforeach;
    }

}
