<?php

namespace App\Http\Controllers\Backend\Panic;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Panic\EloquentPanicRepository;
use Illuminate\Http\Request;

class PanicController extends Controller {

    /**
     * @var EloquentCompanyRepository
     */
    //private $newsfeedRepository;

    private $panicRepository;

    /**
     * AdminCompanyController constructor.
     * @param EloquentCompanyRepository $companyRepository
     */
    public function __construct(EloquentPanicRepository $panicRepository) {

        $this->panicRepository = $panicRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function panicButtonActions($request)  
    {
        $this->panicRepository->findResquers($request);  //find resquers within 5 KM
    }

}
