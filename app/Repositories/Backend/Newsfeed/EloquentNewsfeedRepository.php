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
            $obj->user_id =access()->id();
            $obj->newsfeed_type = (!empty($request->newsfeed_type)) ? $request->newsfeed_type : '';
            $obj->countryid = (!empty($request->countryid)) ? $request->countryid : '';
            $obj->areaid = (!empty($request->areaid)) ? $request->areaid : '';
        }
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
                $newsfeed = Newsfeed::where('areaid', $request->area_id)->where('newsfeed_type',$request->newsfeed_type)->orderBy('newsfeeds.id', 'desc')->paginate(10);
            else
                $newsfeed = Newsfeed::where('areaid', $request->area_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
        } else if (!empty($request->country_id)) {
          if ($request->newsfeed_type != "All")
                $newsfeed = Newsfeed::where('countryid', $request->country_id)->where('newsfeed_type',$request->newsfeed_type)->orderBy('newsfeeds.id', 'desc')->paginate(10); 
            else
                $newsfeed = Newsfeed::where('countryid', $request->country_id)->orderBy('newsfeeds.id', 'desc')->paginate(10);
        } else
            $newsfeed = $this->getNewsfeedPaginated();
        return $newsfeed;
    }

}
