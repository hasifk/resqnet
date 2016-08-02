<?php
namespace App\Http\Controllers\Backend\RescueOperation;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\RescueOperation\AdminOperationRepositoryContract;

class AdminOperationController extends Controller
{
    private $operation;
    public function __construct(AdminOperationRepositoryContract $operation) {

        $this->operation = $operation;
    }

    public function operations()
    {
        $view = [
            'operations' => $this->operation->getOperations(),
        ];
        return view('backend.operations.index', $view);
    }
}