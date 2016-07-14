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
  /*  public function createNewsfeed() {
        return view('backend.admin.newsfeed.create');
    }
    public function saveNewsfeed(NewsfeedRequests $request)
    {
        $array['type'] = 'News Feed';
        $array['heading']='News of  "'.substr($request->newsfeed,0,50).'..."';
        if ($request->has('id')):
            $array['event'] = 'updated';

            $name = $this->userlogs->getActivityDescriptionForEvent($array);
        else:
            $array['event'] = 'created';

            $name = $this->userlogs->getActivityDescriptionForEvent($array);
        endif;

        if($user = $this->newsfeedRepository->save($request)):
            Activity::log($name);
            endif;

        return redirect()->route('backend.admin.newsfeeds');
    }
    public function showNewsfeed($id)
    {
        $view = [
            'newsfeed' => $this->newsfeedRepository->find($id),
        ];
        return view('backend.admin.newsfeed.show',$view);
    }
    public function EditNewsfeed($id)
    {
        $view = [
            'newsfeed' => $this->newsfeedRepository->find($id),
        ];
        return view('backend.admin.newsfeed.edit',$view);
    }
    
    public function DeleteNewsfeed($id)
    {
        $obj = $this->newsfeedRepository->find($id);
        $array['type'] = 'News Feed';
        $array['event'] = 'deleted';
        $array['heading']='Heading of "'.substr($obj->news,0,40).'..."';
        
        $name = $this->userlogs->getActivityDescriptionForEvent($array);
        Activity::log($name);
        
        
        $this->newsfeedRepository->delete($id);
        //Newsfeed::where('id', $id)->delete();
        return redirect()->route('backend.admin.newsfeeds');
    }
    */
}