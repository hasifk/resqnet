<?php

namespace App\Repositories\Backend\RescueOperation;
use App\Models\RescueOperation\Operation;

use Auth;
use Storage;

class EloquentAdminRescueOperationRepository implements AdminOperationRepositoryContract
{


    public function getOperations()
    {
       return Operation::paginate(10);
    }
    public function find($id)
    {
        return Operation::find($id);
    }



}
