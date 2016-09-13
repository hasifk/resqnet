<?php

namespace App\Http\Controllers\Backend\Newsfeed;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Newsfeed\CreateNewsfeedRequest;
use App\Http\Requests\Backend\Newsfeed\EditNewsfeedRequest;
use App\Http\Requests\Backend\Newsfeed\ShowNewsfeedRequest;
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
    public function showNewsfeeds(ShowNewsfeedRequest $request) {
        $newsfeeds = $this->newsfeedRepository->getNewsFeeds($request->user_id);
        if (!empty($newsfeeds)):
            foreach ($newsfeeds as $key=>$item) {
                if ($newsfeeds[$key]['image_filename'] && $newsfeeds[$key]['image_extension'] && $newsfeeds[$key]['image_path']) {

                    $newsfeeds[$key]['newsfeed_image_src']=url('/image/'.$newsfeeds[$key]['id'].'/'.$newsfeeds[$key]['image_filename'].'.'.$newsfeeds[$key]['image_extension']);
                }
            }
            return response()->json(['newsfeeds' => $newsfeeds->toArray()]);
        else:
            return response()->json(['newsfeeds' => 'No newfeed found']);
        endif;
    }

    public function showMyNewsfeeds(ShowNewsfeedRequest $request) {


        if (access()->hasRolesApp(['Police', 'Fire', 'Paramedic'],$request->user_id)):
            $newsfeeds=$this->newsfeedRepository->getMyNewsFeeds($request->user_id);
        if (!empty($newsfeeds)):
            foreach ($newsfeeds as $key=>$item) {
                if ($newsfeeds[$key]['image_filename'] && $newsfeeds[$key]['image_extension'] && $newsfeeds[$key]['image_path']) {

                    $newsfeeds[$key]['newsfeed_image_src']=url('/image/'.$newsfeeds[$key]['id'].'/'.$newsfeeds[$key]['image_filename'].'.'.$newsfeeds[$key]['image_extension']);
                }
            }
            return response()->json(['newsfeeds' => $newsfeeds->toArray()]);
        else:
            return response()->json(['newsfeeds' => 'No newfeed found']);
        endif;
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }
    public function showNewsfeed(ShowNewsfeedRequest $request) {

            if($newsfeed1 = $this->newsfeedRepository->find($request->id)):
            $newsfeed= $newsfeed1->toArray();
            if ($newsfeed1->image_filename && $newsfeed1->image_extension && $newsfeed1->image_path) {

                $newsfeed['newsfeed_image_src']=url('/image/'.$newsfeed1->id.'/'.$newsfeed1->image_filename.'.'.$newsfeed1->image_extension);
            }
            return response()->json(['newsfeed' => $newsfeed]);
         else:
        return response()->json(['newsfeeds' => 'No newfeed found']);
        endif;


    }

    public function createNewsfeed(CreateNewsfeedRequest $request) {
        if (access()->hasRolesAPP(['Police', 'Fire', 'Paramedic'],$request->user_id)):
            return response()->json(['newsfeed' => $this->newsfeedRepository->save($request)->toArray()]);
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }

    public function editNewsfeed(EditNewsfeedRequest $request) {

        if (access()->hasRolesApp(['Police', 'Fire', 'Paramedic'],$request->user_id)):
            $newsfeed1 = $this->newsfeedRepository->find($request->id);
            $newsfeed= $newsfeed1->toArray();
            if ($newsfeed1->image_filename && $newsfeed1->image_extension && $newsfeed1->image_path) {

                $newsfeed['newsfeed_image_src']=url('/image/'.$newsfeed1->id.'/'.$newsfeed1->image_filename.'.'.$newsfeed1->image_extension);
            }
            return response()->json(['newsfeed' => $newsfeed]);
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }


    public function updateNewsfeed(UpdateNewsfeedRequest $request) {
        if (access()->hasRolesApp(['Police', 'Fire', 'Paramedic'],$request->user_id)):
            return response()->json(['newsfeed' => $this->newsfeedRepository->save($request)->toArray()]);
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }

    public function deleteNewsfeed(EditNewsfeedRequest $request) {
        if (access()->hasRolesApp(['Police', 'Fire', 'Paramedic'],$request->user_id)):
            if ($this->newsfeedRepository->delete($request->id)):
                return response()->json(['status' => "Selected Newsfeed has been deleted successfully"]);
            else:
                return response()->json(['status' => "Failed"]);
            endif;
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }

    public function getImage($id,$image)
    {
        try
        {
            $img = \Image::make(storage_path() . '/app/public/newsfeed/image/'.$id.'/' . $image)->response();
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => "Image not found"]);
        }
        return $img;

    }

}
