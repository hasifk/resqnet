<?php

namespace App\Http\Controllers\Backend\RescueOperation;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\RescueOperation\EloquentRescueOperationRepository;
use Illuminate\Http\Request;

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
    
    public function rescueeOperationActions($request)  
    {
       $userdetails=$this->rescueOperationRepository->findActiveRescuers($request);  //find resquers within 5 KM
       response()->json(['rescuee' => $userdetails['rescuee']->toArray(), 'rescuer' => $userdetails['rescuer'],'return_id' =>$userdetails['return_id']]);       
    }
    public function RescuerOperationResponce($request)  
    {
        $this->rescueOperationRepository->rescuersResponce($request);  //save the resquer details once they accepted rescuee requests
    }
    public function RescueeForm()
    {
       return $this->rescueOperationRepository->rescueeForm();  //save the resquer details once they accepted rescuee requests
    }

}
