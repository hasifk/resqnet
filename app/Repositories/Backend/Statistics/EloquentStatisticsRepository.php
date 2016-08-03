<?php

namespace App\Repositories\Backend\Statistics;


use App\Models\RescueOperation\Operation;

use Auth;
use Storage;

class EloquentStatisticsRepository implements StatisticsRepositoryContract
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
