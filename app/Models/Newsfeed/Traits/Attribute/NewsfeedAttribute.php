<?php namespace App\Models\Newsfeed\Traits\Attribute;

use App\Models\Newsfeed\Newsfeed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;

/**
 * Class UserAttribute
 * @package App\Models\Access\User\Traits\Attribute
 */
trait NewsfeedAttribute {

    /**
     * @return string
     */
    
    public function getEditButtonAttribute() {
        if (access()->can('edit-newsfeed'))
            return '<a href="'.route('backend.admin.newsfeed.edit', $this->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('crud.edit_button') . '"></i></a> ';
        return '';
    }
    
    /**
     * @return string
     */
    public function getDeleteButtonAttribute() {
        if (access()->can('delete-newsfeed'))
            return '<a href="'.route('backend.admin.newsfeed.destroy', $this->id).'" class="newsfeed_delete btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="' . trans('crud.delete_button') . '"></i></a>';
        return '';
    }

     public function getShowButtonAttribute(){
        if (access()->can('show-newsfeed'))
            return '<a href="'.route('backend.admin.newsfeedshow', $this->id).'" class="btn btn-xs btn-success"><i class="fa fa-arrow-circle-right" data-toggle="tooltip" data-placement="top" title="View More"></i></a> ';
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
