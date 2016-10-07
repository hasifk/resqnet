<?php

namespace App\Http\Controllers\Backend\Newsfeed;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Newsfeed\NewsFeedRepositoryContract;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;
use Illuminate\Http\Request;
use App\Models\Countries\City;

class AdminNewsfeedController extends Controller {

    //private $newsfeedRepository;
    private $user;
    private $newsfeedRepository;

    public function __construct(NewsFeedRepositoryContract $newsfeedRepository, UserRepositoryContract $user) {

        $this->newsfeedRepository = $newsfeedRepository;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showNewsfeeds() {
     $newsfeeds=$this->newsfeedRepository->getNewsfeedPaginated();
        if (count($newsfeeds)>0):
            foreach ($newsfeeds as $key=>$item) {
                if ($newsfeeds[$key]['image_filename'] && $newsfeeds[$key]['image_extension'] && $newsfeeds[$key]['image_path']) {

                    $newsfeeds[$key]['newsfeed_image_src'] = url('/image/' . $newsfeeds[$key]['id'] . '/' . $newsfeeds[$key]['image_filename'] . '300x168.' . $newsfeeds[$key]['image_extension']);
                }
            }
            endif;
        $view = [
            'newsfeeds' => $newsfeeds,
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
        $newsfeed = $this->newsfeedRepository->findNews($id);
        if ($newsfeed->areaid > 0) {
            $city = City::find($newsfeed->areaid);
            $newsfeed['area'] = $city->name;
        }

        if ($newsfeed->image_filename && $newsfeed->image_extension && $newsfeed->image_path) {

            $newsfeed['newsfeed_image_src']=url('/image/'.$newsfeed->id.'/'.$newsfeed->image_filename.'300x168.'.$newsfeed->image_extension);

        }
        $view = [
            'newsfeed' => $newsfeed,
        ];
        return view('backend.newsfeed.show', $view);
    }
    public function deleteNewsFeed($id) {
        $this->newsfeedRepository->delete($id);
        return redirect(route('admin.newsfeed.index'));
    }

}
