<?php namespace App\Models\Rescuer\Traits\Attribute;

use App\Models\Rescuer\RescuerType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;

/**
 * Class UserAttribute
 * @package App\Models\Access\User\Traits\Attribute
 */
trait DepartmentAttribute {

    /**
     * @return string
     */
    
    public function getEditButtonAttribute() {
        if (access()->allow('edit-department'))
            return '<a href="'.route('backend.admin.department_edit', $this->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a> ';
        return '';
    }
    
    /**
     * @return string
     */
    public function getDeleteButtonAttribute() {
        if (access()->allow('delete-department'))
            return '<a href="'.route('backend.admin.department_delete', $this->id).'" class="department_delete btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
        return '';
    }

    /**
     * @return string
     */
    
    public function getActionButtonsAttribute() {
        return $this->getEditButtonAttribute().
        $this->getDeleteButtonAttribute();
    }
}
