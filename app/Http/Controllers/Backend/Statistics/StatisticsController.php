<?php

namespace App\Http\Controllers\Backend\Statistics;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Statistics\StatisticsRepositoryContract;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;


class StatisticsController extends Controller {
    /**
     * @var EloquentCompanyRepository
     */
    private $statistics;
    private $user;
    public function __construct(StatisticsRepositoryContract $statistics,UserRepositoryContract $user) {

        $this->statistics = $statistics;
        $this->user = $user;
    }

    public function amountOfUsers() {
        $view = [
            'countries' => $this->user->countries(),
            'areas' => $this->user->areas(),
        ];
        return view('backend.statistics.amount_of_users', $view);
    }

}
