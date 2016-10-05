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
            'countries' => $this->user->countries(),
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
         //return response()->json(['result' => $this->notification->save($request)]);
    }

    public function NotificationDelete(Request $request) {
        $states = $this->notification->NotificationDelete($request);
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
