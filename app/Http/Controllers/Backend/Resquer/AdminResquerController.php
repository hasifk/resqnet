<?php

namespace App\Http\Controllers\Backend\Resquer;

use App\Http\Controllers\Controller;
use App\Models\Newsfeed\Resquer;

class AdminResquerController extends Controller {
    /**
     * @var EloquentCompanyRepository
     */
    //private $newsfeedRepository;

    /**
     * AdminCompanyController constructor.
     * @param EloquentCompanyRepository $companyRepository
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showResquerType() {
        $view = [
            'resquer' => Newsfeed::all(),
        ];
        return view('backend.resquer.index', $view);
    }



}
