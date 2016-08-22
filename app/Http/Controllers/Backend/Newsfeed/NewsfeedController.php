<?php

namespace App\Http\Controllers\Backend\Newsfeed;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Newsfeed\CreateNewsfeedRequest;
use App\Models\Newsfeed\Newsfeed;
use App\Repositories\Backend\Newsfeed\EloquentNewsfeedRepository;

class NewsfeedController extends Controller {

    /**
     * @var EloquentCompanyRepository
     */
    private $newsfeedRepository;

    public function __construct(EloquentNewsfeedRepository $newsfeedRepository) {

        $this->newsfeedRepository = $newsfeedRepository;
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

        $newsfeeds = Newsfeed::all();
        if (!empty($newsfeeds)):
            return response()->json(['newsfeeds' => $newsfeeds->toArray()]);
        else:
            return response()->json(['newsfeeds' => 'No newfeed found']);
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
