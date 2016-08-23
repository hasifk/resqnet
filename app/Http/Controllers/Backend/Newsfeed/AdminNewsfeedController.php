<?php

namespace App\Http\Controllers\Backend\Newsfeed;

use App\Http\Controllers\Controller;

use App\Repositories\Backend\Newsfeed\NewsFeedRepositoryContract;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;
use Illuminate\Http\Request;

class AdminNewsfeedController extends Controller {
    
    //private $newsfeedRepository;
     private $user;
     private $newsfeedRepository;
    public function __construct(NewsFeedRepositoryContract $newsfeedRepository,UserRepositoryContract $user) {

        $this->newsfeedRepository = $newsfeedRepository;
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showNewsfeeds() {
        $view = [
            'newsfeeds' => $this->newsfeedRepository->getNewsfeedPaginated(),
            'countries' => $this->user->countries(),
        ];
        return view('backend.newsfeed.index', $view);
    }
    public function newsFeedSearch(Request $request) {
      
         $view = [
            'newsfeeds' => $this->newsfeedRepository->newsFeedSearch($request),
            'countries' => $this->user->countries(),
        ];
        return view('backend.newsfeed.index_new', $view);
    }
     public function newsFeedShow($id) {
      
         $view = [
            'newsfeed' => $this->newsfeedRepository->find($id),
        ];
        return view('backend.newsfeed.show', $view);
    }
}
