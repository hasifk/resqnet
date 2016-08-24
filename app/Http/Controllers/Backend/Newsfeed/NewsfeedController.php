<?php

namespace App\Http\Controllers\Backend\Newsfeed;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Newsfeed\CreateNewsfeedRequest;
use App\Http\Requests\Backend\Newsfeed\UpdateNewsfeedRequest;
use App\Models\Newsfeed\Newsfeed;
use App\Repositories\Backend\Newsfeed\NewsFeedRepositoryContract;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;


class NewsfeedController extends Controller {

    /**
     * @var EloquentCompanyRepository
     */
    private $newsfeedRepository;

    public function __construct(NewsFeedRepositoryContract $newsfeedRepository,UserRepositoryContract $user) {

        $this->newsfeedRepository = $newsfeedRepository;
        $this->user = $user;
    }

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
        $newsfeeds = $this->newsfeedRepository->getNewsFeeds();
        if (!empty($newsfeeds)):
            return response()->json(['newsfeeds' => $newsfeeds->toArray()]);
        else:
            return response()->json(['newsfeeds' => 'No newfeed found']);
        endif;
    }

    public function showMyNewsfeeds() {

        if (access()->hasRoles(['Police', 'Fire', 'Paramedic'])):
            $newsfeeds=$this->newsfeedRepository->getMyNewsFeeds();
        if (!empty($newsfeeds)):
            return response()->json(['newsfeeds' => $newsfeeds->toArray()]);
        else:
            return response()->json(['newsfeeds' => 'No newfeed found']);
        endif;
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }

    public function createNewsfeed(CreateNewsfeedRequest $request) {
        if (access()->hasRoles(['Police', 'Fire', 'Paramedic'])):
            return response()->json(['newsfeed' => $this->newsfeedRepository->save($request)->toArray()]);
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }

    public function editNewsfeed($id) {

        if (access()->hasRoles(['Police', 'Fire', 'Paramedic'])):
            return response()->json(['newsfeed' => $this->newsfeedRepository->find($id)->toArray()]);
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }


    public function updateNewsfeed(UpdateNewsfeedRequest $request) {
        if (access()->hasRoles(['Police', 'Fire', 'Paramedic'])):
            return response()->json(['newsfeed' => $this->newsfeedRepository->save($request)->toArray()]);
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }

    public function deleteNewsfeed($id) {
        if (access()->hasRoles(['Police', 'Fire', 'Paramedic'])):
            if ($this->newsfeedRepository->delete($id)):
                return response()->json(['status' => "Selected Newsfeed has been deleted successfully"]);
            else:
                return response()->json(['status' => "Failed"]);
            endif;
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }

}
