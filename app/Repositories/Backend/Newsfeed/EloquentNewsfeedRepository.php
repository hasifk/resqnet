<?php

namespace App\Repositories\Backend\Newsfeed;

use App\Models\Newsfeed\Newsfeed;
use App\Models\Access\User\User;
use Auth;
use Event;

class EloquentNewsfeedRepository implements NewsFeedRepositoryContract {

    public function getNewsfeedPaginated() {
        return Newsfeed::join('users', 'newsfeeds.user_id', '=', 'users.id')->select('newsfeeds.*', 'users.firstname', 'users.lastname')->orderBy('newsfeeds.id', 'desc')
                        ->paginate(20);
    }

    public function getMyNewsFeeds($user_id) {
        return Newsfeed::where('user_id', $user_id)->orderBy('id', 'desc')->paginate(8);
    }

    public function getNewsFeeds($user_id) {
        $user = User::find($user_id);

        if (access()->hasRolesApp(['Police', 'Fire', 'Paramedic'], $user_id)) {
            return Newsfeed::where('newsfeeds.countryid', '=', $user->country_id)
                            ->whereIn('newsfeeds.newsfeed_type', ['Rescuer', 'All'])
                            ->orWhere('newsfeeds.areaid', '=', $user->area_id)
                            ->whereIn('newsfeeds.newsfeed_type', ['Rescuer', 'All'])
                            ->select('newsfeeds.*')->orderBy('newsfeeds.id', 'desc')
                            ->paginate(8);
        } else if (access()->hasRolesApp(['User'], $user_id)) {
            return Newsfeed::where('newsfeeds.countryid', '=', $user->country_id)
                            ->whereIn('newsfeeds.newsfeed_type', ['User', 'All'])
                            ->orWhere('newsfeeds.areaid', '=', $user->area_id)
                            ->whereIn('newsfeeds.newsfeed_type', ['User', 'All'])
                            ->select('newsfeeds.*')->orderBy('newsfeeds.id', 'desc')->paginate(8);
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

}
