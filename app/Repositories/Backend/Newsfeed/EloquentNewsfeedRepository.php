<?php

namespace App\Repositories\Backend\Newsfeed;

use App\Models\Newsfeed\Newsfeed;
use App\Models\Access\User\User;
use App\Repositories\Backend\UserGroups\UserGroupsRepositoryContract;
use Auth;
use Event;
use Illuminate\Support\Facades\Redirect;

class EloquentNewsfeedRepository implements NewsFeedRepositoryContract {

    private $groups;

    public function __construct(UserGroupsRepositoryContract $groups) {
        $this->groups = $groups;
    }

    public function getNewsfeedPaginated() {
        return Newsfeed::join('users', 'newsfeeds.user_id', '=', 'users.id')->select('newsfeeds.*', 'users.firstname', 'users.lastname')->orderBy('newsfeeds.id', 'desc')
                        ->paginate(20);
    }

    public function getMyNewsFeeds($user_id) {
        return Newsfeed::where('user_id', $user_id)->orderBy('id', 'desc')->paginate(20);
    }

    public function getNewsFeeds($user_id) {
        $user = User::find($user_id);
        $newsfeed_ids = '';
        if (access()->hasRolesApp(['Police', 'Fire', 'Paramedic'], $user_id)) {
            return Newsfeed::where('newsfeeds.countryid', '=', $user->country_id)
                            ->whereIn('newsfeeds.newsfeed_type', ['Rescuer', 'All'])
                            ->orWhere('newsfeeds.areaid', '=', $user->area_id)
                            ->whereIn('newsfeeds.newsfeed_type', ['Rescuer', 'All'])
                            ->select('newsfeeds.*')->orderBy('newsfeeds.id', 'desc')
                            ->paginate(20);
        } else if (access()->hasRolesApp(['User'], $user_id)) {
            if (count($this->groups->joinedGroupLists($user_id)) > 0) {
                $newsfeeds = Newsfeed::where('newsfeed_type', "User Group")->get();
                foreach ($newsfeeds as $newsfeed) {
                    $group_ids = json_decode($newsfeed->group_id);
                    if (!empty($group_ids)):
                        foreach ($group_ids as $id) {
                            if (!empty($this->groups->findMembersUser($user_id, $id))) {
                                $newsfeed_ids[] = $newsfeed->id;
                            }
                        }
                    endif;
                }
                if (count($newsfeed_ids) > 0) {
                    return Newsfeed::where('newsfeeds.countryid', '=', $user->country_id)
                                    ->whereIn('newsfeeds.newsfeed_type', ['User', 'All'])
                                    ->orWhere('newsfeeds.areaid', '=', $user->area_id)
                                    ->whereIn('newsfeeds.newsfeed_type', ['User', 'All'])
                                    ->orWhere(function($query) use($newsfeed_ids) {
                                        $query->whereIn('newsfeeds.id', $newsfeed_ids);
                                    })
                                    ->select('newsfeeds.*')->orderBy('newsfeeds.id', 'desc')->paginate(20);
                } else {
                    return $newsfeed_ids = array();
                }
            } else {
                return Newsfeed::where('newsfeeds.countryid', '=', $user->country_id)
                                ->whereIn('newsfeeds.newsfeed_type', ['User', 'All'])
                                ->orWhere('newsfeeds.areaid', '=', $user->area_id)
                                ->whereIn('newsfeeds.newsfeed_type', ['User', 'All'])
                                ->select('newsfeeds.*')->orderBy('newsfeeds.id', 'desc')->paginate(20);
            }
        }
    }

    public function save($request) {
        if ($request->has('id'))
            $obj = $this->find($request->id);
        else {
            $obj = new Newsfeed;
            $obj->user_id = $request->user_id;
// $obj->newsfeed_type = $request->newsfeed_type;
            $obj->countryid = $request->countryid;
            $obj->areaid = (!empty($request->areaid)) ? $request->areaid : '';
        }
        $obj->newsfeed_type = (!empty($request->newsfeed_type)) ? $request->newsfeed_type : '';
        $obj->news_title = (!empty($request->news_title)) ? $request->news_title : '';
        $obj->news = $request->news;

        $obj->save();
        $obj->attachNewsfeedImage($request->img);
        return $obj;
    }

    public function find($id) {
        return Newsfeed::find($id);
    }

    public function findNews($id) {
        return Newsfeed::join('users', 'newsfeeds.user_id', '=', 'users.id')
                        ->join('countries', 'newsfeeds.countryid', '=', 'countries.id')
                        ->where('newsfeeds.id', $id)
                        ->select('newsfeeds.*', 'users.firstname', 'users.lastname', 'countries.name')
                        ->first();
    }

    public function delete($id) {
        $obj = $this->find($id);
        if ($obj):
            $obj->detachNewsfeedImage();
            $obj->delete();
            return true;
        endif;
    }

    public function newsFeedSearch($request) {

        if (!empty($request->state_id) && !empty($request->area_id)) {
            if ($request->rescur != "")
                $newsfeed = Newsfeed::join('users', 'newsfeeds.user_id', '=', 'users.id')->select('newsfeeds.*', 'users.firstname', 'users.lastname')
                                ->where('newsfeeds.areaid', $request->area_id)->where('newsfeeds.newsfeed_type', $request->rescur)->orderBy('newsfeeds.id', 'desc')->paginate(20);
            else
                $newsfeed = Newsfeed::join('users', 'newsfeeds.user_id', '=', 'users.id')->select('newsfeeds.*', 'users.firstname', 'users.lastname')
                                ->where('newsfeeds.areaid', $request->area_id)->orderBy('newsfeeds.id', 'desc')->paginate(20);
        } else if (!empty($request->country_id)) {
            if ($request->rescur != "")
                $newsfeed = Newsfeed::join('users', 'newsfeeds.user_id', '=', 'users.id')->select('newsfeeds.*', 'users.firstname', 'users.lastname')
                                ->where('newsfeeds.countryid', $request->country_id)->where('newsfeeds.newsfeed_type', $request->rescur)->orderBy('newsfeeds.id', 'desc')->paginate(20);
            else
                $newsfeed = Newsfeed::join('users', 'newsfeeds.user_id', '=', 'users.id')->select('newsfeeds.*', 'users.firstname', 'users.lastname')
                                ->where('newsfeeds.countryid', $request->country_id)->orderBy('newsfeeds.id', 'desc')->paginate(20);
        } else
            $newsfeed = $this->getNewsfeedPaginated();
        return $newsfeed;
    }

    public function timeCalculator($tot_sec) {
        $hours = floor($tot_sec / 3600);
        $minutes = floor(($tot_sec / 60) % 60);
// $seconds = $tot_sec % 60;

        if ($hours >= 1) {
            $time = $hours >= 2 ? $hours . " Hrs Ago" : $hours . " Hr Ago";
            if ($hours >= 24) {
                $days = floor($hours / 24);
                $time = $days . ' Days Ago';
            }
        } else if ($minutes < 1) {
            $time = "Now";
        } else
            $time = $minutes . " Min Ago";
        return $time;
    }

    public function newsfeedNotifications() {
        $newsfeeds_users = array();
        if (!empty($newsfeed = Newsfeed::where('status', 0)->first())) {
            if (!empty($newsfeed->areaid)) {
                if (!empty($users = User::where('area_id', $newsfeed->areaid)->where('id', '!=', $newsfeed->user_id)->get())) {
                    foreach ($users as $user) {
                        if (($newsfeed->newsfeed_type != 'All') && ($user->role_name == $newsfeed->newsfeed_type)) {
                            $newsfeeds_users[] = $user->id;
                        } else if (($newsfeed->newsfeed_type == 'All')) {
                            $newsfeeds_users['id'][$user->id] = $user->id;
                            $newsfeeds_users['app_id'][$user->id] = $user->app_id;
                            $newsfeeds_users['device_type'][$user->id] = $user->device_type;
                        }
                    }
                }
            } else if (!empty($newsfeed->countryid)) {
                if (!empty($users = User::where('country_id', $newsfeed->countryid)->where('id', '!=', $newsfeed->user_id)->get())) {
                    foreach ($users as $user) {
                        if (($newsfeed->newsfeed_type != 'All') && ($user->role_name == $newsfeed->newsfeed_type)) {
                            $newsfeeds_users[] = $user->id;
                        } else if (($newsfeed->newsfeed_type == 'All')) {
                            $newsfeeds_users['id'][$user->id] = $user->id;
                            $newsfeeds_users['app_id'][$user->id] = $user->app_id;
                            $newsfeeds_users['device_type'][$user->id] = $user->device_type;
                        }
                    }
                }
            }
            $newsfeed_id = $newsfeed->id;
            $news_creator1 = User::find($newsfeed->user_id);
            $message['message'] = $news_creator1->firstname . " " . $news_creator1->lastname . " Created a Newsfeed ";
            $message['to'] = "Newsfeed";
            $message['newsfeed_id'] = $newsfeed_id;
            $newsfeed->status = 1;
            $newsfeed->save();
            $this->notification($newsfeeds_users, $message);
        }
    }

    /*     * **************************************************************************************************************** */

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
            if (!defined('API_ACCESS_KEY')) {
                //define('API_ACCESS_KEY', 'AIzaSyD0IORcVqQd4l9lfPTwfuSiThQeB7jj2YQ');
                define('API_ACCESS_KEY', 'AIzaSyBm-1yxRTgj2RWbYfrJqSU2E8iFwmFa8SA');
            }
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
                'newsfeed_id' => $message['newsfeed_id'],
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
            //$tHost = 'gateway.sandbox.push.apple.com';
            $tHost = 'gateway.push.apple.com';
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
                'newsfeed_id' => $message['newsfeed_id'],
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

    /*     * **************************************************************************************************************** */
}
