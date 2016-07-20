<?php

namespace App\Http\Controllers\Backend\Rescuer;

use App\Http\Requests\Backend\Rescuer\DepartmentRequest;
use App\Http\Controllers\Controller;
use App\Models\Rescuer;
use App\Repositories\Backend\Rescuer\EloquentRescuerRepository;
use Illuminate\Http\Request;

class AdminRescuerController extends Controller {

    /**
     * @var EloquentCompanyRepository
     */
    //private $newsfeedRepository;

    private $rescuerRepository;

    /**
     * AdminCompanyController constructor.
     * @param EloquentCompanyRepository $companyRepository
     */
    public function __construct(EloquentRescuerRepository $rescuerRepository) {

        $this->rescuerRepository = $rescuerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRescuerDepts() {
        $view = [
            'departments' => $this->rescuerRepository->getDepartmentPaginated(),
            'types' => $this->rescuerRepository->rescuerType(),
        ];
        return view('backend.department.index', $view);
    }

    public function showRescuerDept($id) {
        $view = [
            'departments' => $this->rescuerRepository->departmentFiltering($id),
            'types' => $this->rescuerRepository->rescuerType(),
        ];
        return view('backend.department.index', $view);
    }
    public function editRescuerDept($id) {
        
        $view = [
            'departments' => $this->rescuerRepository->findDepartment($id),
            'types' => $this->rescuerRepository->rescuerType(),
        ];
        return view('backend.department.edit', $view);
    }

    public function createRescuerDept() {
        $view = [
            'types' => $this->rescuerRepository->rescuerType(),
        ];
        return view('backend.department.create', $view);
    }

    public function saveRescuerDept(DepartmentRequest $request) {

        $this->rescuerRepository->save($request);

        return redirect()->route('backend.admin.rescure_departments');
    }
    public function deleteRescuerDept($id) {
        $this->rescuerRepository->delete($id);
        return redirect()->route('backend.admin.rescure_departments');
    }

}
