<?php

namespace App\Http\Controllers\Backend\RescueOperation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Location\UpdateLocationRequest;
use App\Repositories\Backend\RescueOperation\EloquentRescueOperationRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\RescueeOperation\RescueeOperation;
use App\Http\Requests\Backend\RescuerOperation\RescuerOperation;

class RescueOperationController extends Controller {

    /**
     * @var EloquentCompanyRepository
     */
    //private $newsfeedRepository;

    private $rescueOperationRepository;

    /**
     * AdminCompanyController constructor.
     * @param EloquentCompanyRepository $companyRepository
     */
    public function __construct(EloquentRescueOperationRepository $rescueOperationRepository) {

        $this->rescueOperationRepository = $rescueOperationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function rescueeOperationActions(RescueeOperation $request)
    {
       $userdetails=$this->rescueOperationRepository->findActiveRescuers($request);  //find resquers within 5 KM
       return response()->json(['rescuee' => $userdetails['rescuee']->toArray(), 'rescuer' => $userdetails['rescuer'],'active_rescuers_id' =>$userdetails['active_rescuers_id']]);
    }
    public function rescuerOperationResponse(RescuerOperation $request)
    {
        //save the resquer details once they accepted rescuee requests
        return response()->json(['rescue_operation' =>$this->rescueOperationRepository->rescuersResponse($request)->toArray()]);

    }
    public function rescueeForm()
    {
       return $this->rescueOperationRepository->rescueeForm();  //save the resquer details once they accepted rescuee requests
    }
    public function rescuerLocationUpdates(UpdateLocationRequest $request) {
        return $this->rescueOperationRepository->rescuerLocationUpdates($request);
    }

}
