<?php

namespace App\Repositories\Backend\Newsfeed;


use App\Models\Newsfeed\Newsfeed;
use Auth;
use Event;

class EloquentNewsfeedRepository {

    public function getNewsfeedPaginated() {
        $userid = Auth::user()->id;
        return Newsfeed::where('user_id', $userid)->orderBy('newsfeeds.id', 'desc')
            ->paginate(10);
    }

    public function save($request) {
        $userid = Auth::user()->id;

        if ($request->has('id'))
            $obj = $this->find($request->id);
        else {
            $obj = new Newsfeed;
            $obj->user_id = $userid;
            $obj->resquer_countryid =(!empty($request->resquer_countryid)) ? $request->resquer_countryid : '';
            $obj->resquer_areaid	 =(!empty( $request->resquer_areaid	)) ?  $request->resquer_areaid	: '';
            $obj->user_countryid = (!empty( $request->user_countryid)) ?  $request->user_countryid	: '';
            $obj->user_areaid = (!empty( $request->user_areaid)) ?  $request->user_areaid: '';
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
        if($obj):
            $obj->detachNewsfeedImage();
        endif;
        Newsfeed::where('id', $id)->delete();
        return true;
    }


}
