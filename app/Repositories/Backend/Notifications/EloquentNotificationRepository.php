<?php
namespace App\Repositories\Backend\Notifications;
use App\Models\Notifications\Notification;
use App\Models\Notifications\NotificationCategory;
use Auth;
use Event;

class EloquentNotificationRepository implements NotificationRepositoryContract
{


    public function shows() {
        $userid = Auth::user()->id;
        return Notification::where('user_id', $userid)->orderBy('id', 'desc')
            ->paginate(10);
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
    }

    public function find($id) {
        return Notification::find($id);
    }
    public function filter($request) {
        $userid = Auth::user()->id;
        if(empty($request->country_id))
        return $this->shows();
        else if (!empty($request->state_id) && !empty($request->area_id)) {
            return Notification::where('user_id', $userid)->where('area_id', $request->area_id)->orderBy('id', 'desc')
                ->paginate(10);
        } else{
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
