<?php

namespace App\Http\Controllers\Backend\Notifications;

use App\Models\Notifications\Notification;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Validator;
use App\Repositories\Backend\Notifications\NotificationRepositoryContract;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;
use App\Http\Requests\Backend\Notifications\Notifications;

class NotificationController extends Controller {

    private $notification;

    public function __construct(NotificationRepositoryContract $notification, UserRepositoryContract $user) {

        $this->notification = $notification;
        $this->user = $user;
    }

    public function notifications() {
        $view = [
            'notification' => $this->notification->shows(),
            'category' => $this->notification->category(),
            'countries' => $this->user->countries(),
        ];
        return view('backend.notifications.index', $view);
    }

    public function notification($id) {

        $view = [
            'notification' => $this->notification->show($id),
            'category' => $this->notification->category(),
        ];
        return view('backend.notifications.index', $view);
    }

    public function create() {
        $view = [
            'category' => $this->notification->category(),
            'countries' => $this->user->countries(),
            'areas' => $this->user->areas(),
        ];
        return view('backend.notifications.create', $view);
    }

    public function notificationSave(Notifications $request) {

        $this->notification->save($request);

        return redirect(route('backend.admin.notifications'));
//        $rules = [
//            'notification' => 'required',
//        ];
//        $admin = Auth::user()->id;
//        $role = Auth::user()->role;
//        $return = Auth::user()->roleAccess('push_notification');
//        $this->validator = Validator::make($request->all(), $rules);
//        if ($this->validator->fails()) {
//            return redirect($return)
//                            ->withErrors($this->validator)
//                            ->withInput();
//        } else {
//            $result = Model\Appinfo::where('admin_id', $admin)->get();
//            if (count($result) > 0) {
//                foreach ($result as $val) {
//                    if ($val->device_type == 'Android') {
//                        $app_id1[] = $val->app_id;
//                    } else {
//                        $app_id2[] = $val->app_id;
//                    }
//                }
//                if (!empty($app_id1) && count($app_id1)>0) {
//
//// API access key from Google API's Console
//                    define('API_ACCESS_KEY', 'AIzaSyDPQxOac0sXH7VZEa79R45hCuJjXTn0X8g');
//
//// prep the bundle
//
//                    $msg = array
//                        (
//                        'message' => $request->notification,
//                        'title' => "Notification",
//                        'subtitle' => 'This is a subtitle. subtitle',
//                        'tickerText' => 'Ticker text here...Ticker text here...Ticker text here',
//                        'vibrate' => 1,
//                        'sound' => 1,
//                        'largeIcon' => 'large_icon',
//                        'smallIcon' => 'small_icon'
//                    );
//                    $fields = array
//                        (
//                        'registration_ids' => $app_id1,
//                        'data' => $msg
//                    );
//
//                    $headers = array
//                        (
//                        'Authorization: key=' . API_ACCESS_KEY,
//                        'Content-Type: application/json'
//                    );
//
//                    $ch = curl_init();
//                    curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
//                    curl_setopt($ch, CURLOPT_POST, true);
//                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//                    $result = curl_exec($ch);
//// Close connection
//                    curl_close($ch);
//                }
//                if(!empty($app_id2) && count($app_id2)>0) {
//// Provide the Host Information.
//                    $tHost = 'gateway.sandbox.push.apple.com';
//                    //$tHost = 'gateway.push.apple.com';
//                    $tPort = 2195;
//// Provide the Certificate and Key Data.
//                    $tCert = base_path('public/assets/clientassets/') . 'pushcert.pem';
//
//// Provide the Private Key Passphrase (alternatively you can keep this secrete
//// and enter the key manually on the terminal -> remove relevant line from code).
//// Replace XXXXX with your Passphrase
//                    $tPassphrase = 'SilverBloom1978';
//// Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).
//// Replace this token with the token of the iOS device that is to receive the notification.
////$tToken = 'b3d7a96d5bfc73f96d5bfc73f96d5bfc73f7a06c3b0101296d5bfc73f38311b4';
//                    $tToken = $app_id2;
////0a32cbcc8464ec05ac3389429813119b6febca1cd567939b2f54892cd1dcb134
//// The message that is to appear on the dialog.
//                    $tAlert = $request->notification;
//// The Badge Number for the Application Icon (integer >=0).
//                    $tBadge = 8;
//// Audible Notification Option.
//                    $tSound = 'default';
//// The content that is returned by the LiveCode "pushNotificationReceived" message.
//                    $tPayload = 'APNS Message Handled by LiveCode';
//// Create the message content that is to be sent to the device.
//                    $tBody['aps'] = array(
//                        'alert' => $tAlert,
//                        'badge' => $tBadge,
//                        'sound' => $tSound,
//                    );
//                    $tBody ['payload'] = $tPayload;
//// Encode the body to JSON.
//                    $tBody = json_encode($tBody);
//// Create the Socket Stream.
//                    $tContext = stream_context_create();
//                    stream_context_set_option($tContext, 'ssl', 'local_cert', $tCert);
//// Remove this line if you would like to enter the Private Key Passphrase manually.
//                    stream_context_set_option($tContext, 'ssl', 'passphrase', $tPassphrase);
//// Open the Connection to the APNS Server.
//                    $tSocket = stream_socket_client('ssl://' . $tHost . ':' . $tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $tContext);
//// Check if we were able to open a socket.
//                    if (!$tSocket)
//                        exit("APNS Connection Failed: $error $errstr" . PHP_EOL);
//// Build the Binary Notification.
//                    $tMsg = chr(0) . chr(0) . chr(32) . pack('H*', $tToken) . pack('n', strlen($tBody)) . $tBody;
//
//                    // Ensure that blocking is disabled
//                    stream_set_blocking($tSocket, 0);
//                    //stream_set_blocking($tSocket, 0);
//// Send the Notification to the Server.
//                    $tResult = fwrite($tSocket, $tMsg, strlen($tMsg));
//
//                    // $tResult = fwrite($tSocket, $tMsg);
////            if ($tResult)
////                return 'Delivered Message to APNS' . PHP_EOL;
////            else
////                return 'Could not Deliver Message to APNS' . PHP_EOL;
//                    //Close the Connection to the Server.
//                    fclose($tSocket);
//                }
//                $obj = new Model\Notifications;
//                $obj->admin_id = $admin;
//                $obj->notification = $request->notification;
//                $obj->save();
//                return redirect($return);
//            }
//        }
    }

    public function NotificationDelete(Request $request) {
        $ids = explode(",", $request->id);
        foreach ($ids as $value):
           Notification::where('id', $value)->delete();
        endforeach;
    }

    public function states($id) {
        $states = $this->user->states($id);
        return response()->json($states);
    }
     public function areas($id) {
        $areas = $this->user->cities($id);
        return response()->json($areas);
    }
    public function search(Request $request) {
         $view = [
            'notification' => $this->notification->filter($request),
             'category' => $this->notification->category(),
            'countries' => $this->user->countries(),
        ];
       return view('backend.notifications.index_new', $view);
    }

}
