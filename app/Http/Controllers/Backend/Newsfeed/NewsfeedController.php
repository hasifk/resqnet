<?php

namespace App\Http\Controllers\Backend\Newsfeed;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Newsfeed\CreateNewsfeedRequest;
use App\Http\Requests\Backend\Newsfeed\EditNewsfeedRequest;
use App\Http\Requests\Backend\Newsfeed\ShowNewsfeedRequest;
use App\Http\Requests\Backend\Newsfeed\UpdateNewsfeedRequest;
use App\Models\Newsfeed\Newsfeed;
use Carbon\Carbon;
use App\Repositories\Backend\Newsfeed\NewsFeedRepositoryContract;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;
use App\Models\Access\User\User;



class NewsfeedController extends Controller {

  
    private $newsfeedRepository;
    private $user;
 

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
        if (count($newsfeeds)>0):
            foreach ($newsfeeds as $key=>$item) {
                if ($newsfeeds[$key]['image_filename'] && $newsfeeds[$key]['image_extension'] && $newsfeeds[$key]['image_path']) {

                    $newsfeeds[$key]['newsfeed_image_src']=url('/image/'.$newsfeeds[$key]['id'].'/'.$newsfeeds[$key]['image_filename'].'300x168.'.$newsfeeds[$key]['image_extension']);
                    $newsfeeds[$key]['newsfeed_90x90_src']=url('/image/'.$newsfeeds[$key]['id'].'/'.$newsfeeds[$key]['image_filename'].'90x90.'.$newsfeeds[$key]['image_extension']);

                }

                $user=User::find($newsfeeds[$key]['user_id']);
                if($empty($user)){
                $newsfeeds[$key]['rescuer_name']=$user->firstname." ".$user->lastname;
                $operationtime = strtotime($newsfeeds[$key]['created_at']);
                $mytime = Carbon::now();
                $finishedtime=strtotime($mytime->toDateTimeString());
                $tot_sec = round(abs($finishedtime - $operationtime));
                $time=$this->newsfeedRepository->timeCalculator($tot_sec);
                $newsfeeds[$key]['time']=$time;
                }
            }
            return response()->json(['newsfeeds' => $newsfeeds->toArray()]);
        else:
            return response()->json(['newsfeeds' => 'No NewsFeed found']);
        endif;
    }

    public function showMyNewsfeeds(ShowNewsfeedRequest $request) {
        if (access()->hasRolesApp(['Police', 'Fire', 'Paramedic'],$request->user_id)):
            $newsfeeds=$this->newsfeedRepository->getMyNewsFeeds($request->user_id);
        if (count($newsfeeds)>0):
            foreach ($newsfeeds as $key=>$item) {
                if ($newsfeeds[$key]['image_filename'] && $newsfeeds[$key]['image_extension'] && $newsfeeds[$key]['image_path']) {

                    $newsfeeds[$key]['newsfeed_image_src']=url('/image/'.$newsfeeds[$key]['id'].'/'.$newsfeeds[$key]['image_filename'].'300x168.'.$newsfeeds[$key]['image_extension']);
                }
            }
            return response()->json(['newsfeeds' => $newsfeeds->toArray()]);
        else:
            return response()->json(['newsfeeds' => 'No NewsFeed found']);
        endif;
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;
    }
    public function showNewsfeed(ShowNewsfeedRequest $request) {

            if($newsfeed1 = $this->newsfeedRepository->find($request->id)):
            $newsfeed= $newsfeed1->toArray();
            $user=User::find($newsfeed1->user_id);
             $operationtime = strtotime($newsfeed1->created_at);
             $mytime = Carbon::now();
             $finishedtime=strtotime($mytime->toDateTimeString());
             $tot_sec = round(abs($finishedtime - $operationtime));
             $time=$this->newsfeedRepository->timeCalculator($tot_sec);
            if ($newsfeed1->image_filename && $newsfeed1->image_extension && $newsfeed1->image_path) {

                $newsfeed['newsfeed_image_src']=url('/image/'.$newsfeed1->id.'/'.$newsfeed1->image_filename.'300x168.'.$newsfeed1->image_extension);
               
            }
             $newsfeed['username']=$user->firstname." ".$user->lastname;
                $newsfeed['time']=$time;
            return response()->json(['newsfeed' => $newsfeed]);
         else:
        return response()->json(['newsfeeds' => 'No NewsFeed found']);
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

                $newsfeed['newsfeed_image_src']=url('/image/'.$newsfeed1->id.'/'.$newsfeed1->image_filename.'300x168.'.$newsfeed1->image_extension);
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
            $img = \Image::make(storage_path() . '/app/public/newsfeed/image/'.$id.'/' . $image)->orientate()->encode('jpg', 90)->response();
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => "Image not found"]);
        }
        return $img;

    }

}
