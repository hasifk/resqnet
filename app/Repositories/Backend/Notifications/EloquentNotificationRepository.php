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
        $results = Notification::join('notifcategories', 'notifications.notif_cat', '=', 'notifcategories.id')
                ->where('notifications.user_id', $userid)->orderBy('notifications.id', 'desc')->select('notifications.*', 'notifcategories.category')
                ->paginate(20);
        foreach ($results as $key => $value) {
            if (!empty($value->country_id))
                $results[$key]['country'] = Country::find($value->country_id)->value('name');
            if (!empty($value->area_id))
                $results[$key]['area'] = City::find($value->area_id)->value('name');
        }
        return $results;
    }

    public function show($id) {

        $userid = Auth::user()->id;
        return Notification::where('user_id', $userid)->orderBy('id', 'desc')
                        ->paginate(20);
    }

    public function category() {
        return NotificationCategory::get();
    }

    public function save($request) {
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

            $userid = Auth::user()->id;
            //$userid=1;
            $obj = new Notification;
            $obj->user_id = $userid;
            $obj->notif_cat = $request->notif_cat;
            $obj->country_id = (!empty($request->country_id)) ? $request->country_id : '';
            $obj->area_id = (!empty($request->area_id)) ? $request->area_id : '';
            $obj->notification = $request->notification;
            $obj->save();
            $message = $request->notification;
            $this->notification($app_id, $message);
        }
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
                    'Authorization: key=AIzaSyD0IORcVqQd4l9lfPTwfuSiThQeB7jj2YQ',
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
                // echo $result;
            } else {
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
                //$tToken ='a18792a07ae2c4caf346332e4fbe5ba8071d5b6d66ef6cd3731f6c78ecdc258a';
                $tToken = $app_id['app_id'][$key];
//0a32cbcc8464ec05ac3389429813119b6febca1cd567939b2f54892cd1dcb134
// The message that is to appear on the dialog.
                $tAlert = $message;
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
//                    'panicid' => $message['id'],
//                    'notification_type' => $message['to']
                );
                $tBody ['payload'] = $tPayload;
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
// Build the Binary Notification.
                $tMsg = chr(0) . chr(0) . chr(32) . pack('H*', $tToken) . pack('n', strlen($tBody)) . $tBody;

// Ensure that blocking is disabled
                stream_set_blocking($tSocket, 0);
//stream_set_blocking($tSocket, 0);
// Send the Notification to the Server.
                $tResult = fwrite($tSocket, $tMsg, strlen($tMsg));

                $tResult = fwrite($tSocket, $tMsg);

//                if ($tResult)
//                    return 'Delivered Message to APNS';
//                else
//                    return 'Could not Deliver Message to APNS';
//Close the Connection to the Server.
                fclose($tSocket);
            }
        }
        //return $fields;
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
