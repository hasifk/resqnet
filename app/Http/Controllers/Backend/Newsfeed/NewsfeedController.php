<?php

namespace App\Http\Controllers\Backend\Newsfeed;

use App\Http\Controllers\Controller;
use App\Models\Newsfeed\Newsfeed;
use App\Http\Requests\Newsfeed\CreateNewsfeedRequest;

class NewsfeedController extends Controller {
    /**
     * @var EloquentCompanyRepository
     */
    //private $newsfeedRepository;

    /**
     * AdminCompanyController constructor.
     * @param EloquentCompanyRepository $companyRepository
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showNewsfeeds() {
        $view = [
            'newsfeeds' => Newsfeed::all(),
        ];
        return view('backend.newsfeed.index', $view);
    }

    public function createNewsfeed(CreateNewsfeedRequest $request) {
        return Newsfeed::create(['user_id' =>'','resquer_roleid'=>$request->resquer_roleid,
            'resquer_countryid'=>$request->resquer_countryid,
            'resquer_areaid'=>$request->resquer_countryid,
            'user_countryid'=>$request->user_countryid,
            'user_areaid'=>$request->user_areaid,
            'news'=>$request->news,
            'image_filename'=>$request->image_filename,
            'image_extension'=>$request->image_extension,
            'image_path'=>$request->image_path
            ])->toArray();
    }
    public function editNewsfeed($id) {
        return Newsfeed::find($id)->toArray();
    }
     public function deleteNewsfeed($id) {
        return Newsfeed::where('id',$id)->delete();
    }

}
