<?php

namespace App\Repositories\Backend\Newsfeed;

use App\Models\Newsfeed\Newsfeed;
use Auth;
use Event;

class EloquentNewsfeedRepository implements NewsFeedRepositoryContract {

    public function getNewsfeedPaginated() {
        return Newsfeed::orderBy('newsfeeds.id', 'desc')
                        ->paginate(10);
    }

    public function getMyNewsFeeds() {
        return Newsfeed::where('user_id', access()->id())->orderBy('id', 'desc')->get();
    }

    public function getNewsFeeds() {
        if (access()->hasRoles(['Police', 'Fire', 'Paramedic'])){
            return Newsfeed::join('users', function ($join) {
                                $join->on('newsfeeds.countryid', '=', 'users.country_id')->orOn('newsfeeds.areaid', '=', 'users.area_id');
                            })->join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                            ->whereIn('assigned_roles.role_id', [2, 3, 4])
                            ->whereIn('newsfeeds.newsfeed_type', ['Rescuer','All'])
                            ->select('newsfeeds.id','newsfeeds.news_title')->get();
        }
        else if(access()->hasRoles(['User'])){
            return Newsfeed::join('users', function ($join) {
                                $join->on('newsfeeds.countryid', '=', 'users.country_id')->orOn('newsfeeds.areaid', '=', 'users.area_id');
                            })->join('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                            ->where('assigned_roles.role_id', 5)
                            ->whereIn('newsfeeds.newsfeed_type', ['User','All'])
                            ->select('newsfeeds.id')->get();
        }
    }

    public function save($request) {
        if ($request->has('id'))
            $obj = $this->find($request->id);
        else {
            $obj = new Newsfeed;
            $obj->user_id = access()->id();
            $obj->newsfeed_type = $request->newsfeed_type;
            $obj->countryid = $request->countryid;
            $obj->areaid = (!empty($request->areaid)) ? $request->areaid : '';
        }
        $obj->newsfeed_type = (!empty($request->newsfeed_type)) ? $request->newsfeed_type : '';
        $obj->news_title = $request->news_title;
        $obj->news = $request->news;

        $obj->save();
        $obj->attachNewsfeedImage($request->img);
        return $obj;
    }

    public function find($id) {
        return Newsfeed::find($id);
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
            if ($request->newsfeed_type != "All")
                $newsfeed = Newsfeed::where('areaid', $request->area_id)->where('newsfeed_type', $request->newsfeed_type)->orderBy('newsfeeds.id', 'desc')->paginate(10);
            else
                $newsfeed = Newsfeed::where('areaid', $request->area_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
        } else if (!empty($request->country_id)) {
            if ($request->newsfeed_type != "All")
                $newsfeed = Newsfeed::where('countryid', $request->country_id)->where('newsfeed_type', $request->newsfeed_type)->orderBy('newsfeeds.id', 'desc')->paginate(10);
            else
                $newsfeed = Newsfeed::where('countryid', $request->country_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
        } else
            $newsfeed = $this->getNewsfeedPaginated();
        return $newsfeed;
    }

}
