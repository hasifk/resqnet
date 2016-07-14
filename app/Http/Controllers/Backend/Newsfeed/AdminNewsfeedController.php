<?php
namespace App\Http\Controllers\Backend\Newsfeed;
use App\Http\Controllers\Controller;
use App\Models\Newsfeed\Newsfeed;

class AdminNewsfeedController extends Controller {

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

}