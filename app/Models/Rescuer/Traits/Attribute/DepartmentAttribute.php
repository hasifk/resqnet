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
        if (access()->can('edit-department'))
            return '<a href="'.route('backend.admin.department_edit', $this->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('crud.edit_button') . '"></i></a> ';
        return '';
    }
    
    /**
     * @return string
     */
    public function getDeleteButtonAttribute() {
        if (access()->can('delete-department'))
            return '<a href="'.route('backend.admin.department_edit', $this->id).'" class="newsfeed_delete btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="' . trans('crud.delete_button') . '"></i></a>';
        return '';
    }

     public function getShowButtonAttribute(){
        if (access()->can('show-department'))
            return '<a href="'.route('backend.admin.department_edit', $this->id).'" class="btn btn-xs btn-success"><i class="fa fa-arrow-circle-right" data-toggle="tooltip" data-placement="top" title="View More"></i></a> ';
        return '';
    }

    /**
     * @return string
     */
    
    public function getActionButtonsAttribute() {
        return $this->getShowButtonAttribute().
        $this->getEditButtonAttribute().
        $this->getDeleteButtonAttribute();
    }
}
