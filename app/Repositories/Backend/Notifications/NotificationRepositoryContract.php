<?php

namespace App\Repositories\Backend\Notifications;

use App\Models\Notifications\Notifications;
use App\Models\Notifications\NotificationCategory;
use Auth;
use Event;
use Illuminate\Http\Request;

class NotificationRepositoryContract {

    public function shows() {
        $userid = Auth::user()->id;
        return Notifications::where('user_id', $userid)->orderBy('id', 'desc')
                        ->paginate(10);
    }

    public function show($id) {

        $userid = Auth::user()->id;
        return Notifications::where('user_id', $userid)->orderBy('id', 'desc')
                        ->paginate(10);
    }

    public function category() {
        return NotificationCategory::get();
    }

    public function save($request) {
        $userid = Auth::user()->id;
        $obj = new Notifications;
        $obj->user_id = $userid;
        $obj->notif_cat = $request->notif_cat;
        $obj->country_id = (!empty($request->country_id)) ? $request->country_id : '';
        $obj->area_id = (!empty($request->area_id)) ? $request->area_id : '';
        $obj->notification = $request->notification;

        $obj->save();
    }

    public function find($id) {
        return Notifications::find($id);
    }

    public function delete($id) {
        $obj = $this->find($id);
        if ($obj):
            $obj->detachNewsfeedImage();
        endif;
        Notifications::where('id', $id)->delete();
        return true;
    }

    public function filter($request) {
        $userid = Auth::user()->id;
        if (!empty($request->state_id) && !empty($request->area_id)) {
            return Notifications::where('user_id', $userid)->where('area_id', $request->area_id)->orderBy('id', 'desc')
                            ->paginate(10);
        } else {
            return Notifications::where('user_id', $userid)->where('country_id', $request->country_id)->orderBy('id', 'desc')
                            ->paginate(10);
        }
    }

}
