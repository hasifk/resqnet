<?php

namespace App\Repositories\Backend\Newsfeed;

use App\Models\Newsfeed\Newsfeed;
use Auth;
use Event;

class EloquentNewsfeedRepository {

    public function getNewsfeedPaginated() {
        return Newsfeed::orderBy('newsfeeds.id', 'desc')
                        ->paginate(10);
    }

    public function save($request) {
        if ($request->has('id'))
            $obj = $this->find($request->id);
        else {
            $obj = new Newsfeed;
            $obj->user_id = $request->user_id;
            $obj->resquer_countryid = (!empty($request->resquer_countryid)) ? $request->resquer_countryid : '';
            $obj->resquer_areaid = (!empty($request->resquer_areaid)) ? $request->resquer_areaid : '';
            $obj->user_countryid = (!empty($request->user_countryid)) ? $request->user_countryid : '';
            $obj->user_areaid = (!empty($request->user_areaid)) ? $request->user_areaid : '';
        }

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
        endif;
        Newsfeed::where('id', $id)->delete();
        return true;
    }

    public function newsFeedSearch($request) {

        if (!empty($request->state_id) && !empty($request->area_id)) {
            if ($request->rescur == "Rescuer")
                $newsfeed = Newsfeed::where('resquer_areaid', $request->area_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
            else if ($request->rescur == "Rescuee")
                $newsfeed = Newsfeed::where('user_areaid', $request->area_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
            else
                $newsfeed = Newsfeed::where('resquer_areaid', $request->area_id)->orWhere('user_areaid', $request->area_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
        } else if (!empty($request->country_id)) {
            if ($request->rescur == "Rescuer")
                $newsfeed = Newsfeed::where('resquer_countryid', $request->country_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
            else if ($request->rescur == "Rescuee")
                $newsfeed = Newsfeed::where('user_countryid', $request->country_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
            else
                $newsfeed = Newsfeed::where('resquer_countryid', $request->country_id)->orWhere('user_countryid', $request->country_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
        } else
            $newsfeed = $this->getNewsfeedPaginated();
        return $newsfeed;
    }

}
