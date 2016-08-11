<?php

namespace App\Repositories\Backend\Statistics;

/**
 * Interface UserRepositoryContract
 * @package App\Repositories\Frontend\User
 */
interface StatisticsRepositoryContract {

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    public function getUserAmount($request);

    public function getAmountOfUsers();
    
    public function getAmountOfRescuers();
    
    public function getRescuerAmount($request);
}
