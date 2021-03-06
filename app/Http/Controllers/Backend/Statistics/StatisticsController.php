<?php

namespace App\Http\Controllers\Backend\Statistics;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Statistics\StatisticsRepositoryContract;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;
use App\Repositories\Backend\RescueOperation\EloquentRescueOperationRepository;
use App\Repositories\Backend\RescueOperation\AdminOperationRepositoryContract;
use Illuminate\Http\Request;

class StatisticsController extends Controller {

    /**
     * @var EloquentCompanyRepository
     */
    private $statistics;
    private $user;
    private $rescueOperationRepository;

    public function __construct(StatisticsRepositoryContract $statistics, UserRepositoryContract $user, EloquentRescueOperationRepository $rescueOperationRepository) {

        $this->statistics = $statistics;
        $this->user = $user;
        $this->rescueOperationRepository = $rescueOperationRepository;
    }

    /*     * **************************************************************************************************************** */

    public function amountOfUsers() {
        $view = [
            'countries' => $this->user->countries(),
            'amount' => $this->statistics->getAmountOfUsers(),
        ];
        return view('backend.statistics.amount_of_users', $view);
    }

    /*     * ***************************************************************************************************** */

    public function userAmount(Request $request) {
        $result = $this->statistics->getUserAmount($request);
        $view = [
            'place' => $result['country'],
            'amount' => $result['amount']
        ];
        return $view;
    }

    /*     * ***************************************************************************************************** */

    public function amountOfRescuers() {
        $view = [
            'countries' => $this->user->countries(),
            'rescuertype' => $this->user->rescuerTypeDetails(),
            'amount' => $this->statistics->getAmountOfRescuers(),
        ];
        return view('backend.statistics.amount_of_rescuers', $view);
    }

    /*     * ***************************************************************************************************** */

    public function rescuerAmount(Request $request) {
        $result = $this->statistics->getRescuerAmount($request);
        $view = [
            'place' => $result['country'],
            'amount' => $result['amount'],
            'type' => $result['type'],
        ];
        return $view;
    }

    /*     * ***************************************************************************************************** */

    public function amountOfNewsfeeds() {
        $view = [
            'countries' => $this->user->countries(),
            'rescuertype' => $this->user->rescuerTypeDetails(),
            'amount' => $this->statistics->getAmountOfNewsfeeds(),
        ];
        return view('backend.statistics.amount_of_newsfeeds', $view);
    }

    /*     * ***************************************************************************************************** */

    public function newsfeedAmount(Request $request) {
        $result = $this->statistics->getNewsfeedAmount($request);
        $view = [
            'place' => $result['country'],
            'amount' => $result['amount']
        ];
        return $view;
    }

    /*     * ***************************************************************************************************** */

    public function amountOfPanicSignals() {
        $view = [
            'countries' => $this->user->countries(),
            'rescuertype' => $this->user->rescuerTypeDetails(),
            'amount' => $this->rescueOperationRepository->ActiveRescuerAll()->count(),
        ];
        return view('backend.statistics.amount_of_panicsignals', $view);
    }

    /*     * ***************************************************************************************************** */

    public function panicsignalAmount(Request $request) {
        $result = $this->statistics->getPanicSignalAmount($request);
        $view = [
            'place' => $result['country'],
            'area' => $result['area'],
            'amount' => $result['amount']
        ];
        return $view;
    }

    /*     * ***************************************************************************************************** */

    public function listsOfRescuers() {
        $view = [
            'countries' => $this->user->countries(),
            'rescuertype' => $this->user->rescuerTypeDetails(),
            'lists' => $this->rescueOperationRepository->listsOfRescuers()
        ];
        return view('backend.statistics.listsofrescuers', $view);
        
    }
    public function listsOfRescuer($id) {
        $view = [
            'countries' => $this->user->countries(),
            'rescuertype' => $this->user->rescuerTypeDetails(),
            'list' => $this->rescueOperationRepository->listsOfRescuer($id)
        ];
        return view('backend.statistics.listsofrescuers_show', $view);
        
    }
    public function rescuersLists(Request $request)
    {
       $result = $this->statistics->getPanicSignalAmount($request);
        $view = [
           'lists' => $this->rescueOperationRepository->rescuersLists($result['lists'])
        ];
       return view('backend.statistics.listsofrescuers_new', $view);
        //return response()->json(['emergencycontacts' => $this->rescueOperationRepository->listsOfRescuers($result['lists'])]);
    }
    public function deletePanic(Request $request) {
       $this->statistics->panicDelete($request);
    }

}
