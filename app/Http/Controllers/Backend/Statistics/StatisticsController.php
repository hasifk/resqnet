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
            'amount'=> $this->statistics->getAmountOfUsers(),
        ];
        return view('backend.statistics.amount_of_users', $view);
    }

    /********************************************************************************************************/
    public function userAmount(Request $request) {
        $result=$this->statistics->getUserAmount($request);
        $view = [
            'place' => $result['country'],
            'amount' =>$result['amount']

        ];
        return $view;
    }
    /********************************************************************************************************/
    public function amountOfRescuers() {
        $view = [
            'countries' => $this->user->countries(),
            'areas' => $this->user->areas(),
            'amount'=> $this->statistics->getAmountOfRescuers(),
        ];
        return view('backend.statistics.amount_of_rescuers', $view);
    }
/********************************************************************************************************/
    public function rescuerAmount(Request $request) {
        $result=$this->statistics->getRescuerAmount($request);
        $view = [
            'place' => $result['country'],
            'amount' =>$result['amount']

        ];
        return $view;
    }
}
