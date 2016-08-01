<?php

namespace App\Repositories\Backend\RescueOperation;

/**
 * Interface UserRepositoryContract
 * @package App\Repositories\Frontend\User
 */
interface AdminOperationRepositoryContract
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);


    public function getOperations();
}
