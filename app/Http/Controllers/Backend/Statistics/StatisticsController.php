<?php

namespace App\Http\Controllers\Backend\Statistics;

use App\Http\Controllers\Controller;


class StatisticsController extends Controller {
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

}
