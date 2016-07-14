<?php

namespace App\Http\Controllers\Backend\Newsfeed;

/*use App\Http\Requests\Backend\Admin\Newsfeed\NewsfeedRequests;*/
/*use App\Models\Newsfeed\Newsfeed;*/
/*use App\Repositories\Backend\Newsfeed\EloquentNewsfeedRepository;
use App\Repositories\Backend\Logs\LogsActivitysRepository;*/
use App\Models\Newsfeed\Newsfeed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Activity;

class AdminNewsfeedController extends Controller {

    /**
     * @var EloquentCompanyRepository
     */
    //private $newsfeedRepository;

    /**
     * AdminCompanyController constructor.
     * @param EloquentCompanyRepository $companyRepository
     */
   /* public function __construct(EloquentNewsfeedRepository $newsfeedRepository,LogsActivitysRepository $userlogs) {

        $this->newsfeedRepository = $newsfeedRepository;
        $this->userlogs = $userlogs;
    }*/

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

}