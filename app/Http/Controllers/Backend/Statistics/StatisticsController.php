<?php

namespace App\Http\Controllers\Backend\Statistics;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Statistics\StatisticsRepositoryContract;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;
use Illuminate\Http\Request;


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
/*******************************************************************************************************************/
    public function amountOfUsers() {
        $view = [
            'countries' => $this->user->countries(),
            'areas' => $this->user->areas(),
        ];
        return view('backend.statistics.amount_of_users', $view);
    }

    /********************************************************************************************************/
    public function checkCountry(Request $request) {
        $result=$this->statistics->getUsersbyCountry($request);
        $view = [
            'place' => $result['country'],
            'amount' =>$result['amount']

        ];
        return view('backend.statistics.amount_of_users_result', $view);
    }
    /********************************************************************************************************/
    public function checkArea(Request $request) {
        $result=$this->statistics->getUsersbyArea($request);
        $view = [
            'place' => $result['area'],
            'amount' =>$result['amount']

        ];
        return view('backend.statistics.amount_of_users_result', $view);
    }

}
