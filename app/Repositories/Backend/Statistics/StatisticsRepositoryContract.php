<?php

namespace App\Repositories\Backend\Statistics;

/**
 * Interface UserRepositoryContract
 * @package App\Repositories\Frontend\User
 */
interface StatisticsRepositoryContract
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);


    public function getUsersbyCountry($request);
    public function getUsersbyArea($request);

}
