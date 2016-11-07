<?php

namespace App\Models\RescueOperation\Traits\Attribute;

use App\Models\Access\User\User;

/**
 * Class UserAttribute
 * @package App\Models\Access\User\Traits\Attribute
 */
trait OperationAttribute {

    public function getRescueeNameAttribute() {
        if (is_null($this->rescuee_id)) {
            return null;
        }
        return User::where('id', $this->rescuee_id)->value('firstname');
    }

    public function getRescuerNameAttribute() {
        if (is_null($this->rescuer_id)) {
            return null;
        }
        return User::where('id', $this->rescuer_id)->value('firstname');
    }

    public function getOperationStatusAttribute() {
        if (is_null($this->finished_at)) {
            return 'not finished';
        }
        return 'finished';
    }

    /**
     * @return string
     */
    public function getShowButtonAttribute() {
        if (access()->allow('show-users'))
            return '<a href="' . route('admin.statistics.listsofrescuer', $this->id) . '" class="btn btn-xs btn-success"><i class="fa fa-arrow-circle-right" data-toggle="tooltip" data-placement="top" title="View More"></i></a> ';
        return '';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute() {
        if (access()->allow('delete-newsfeed'))
            return '<a href="' . route('admin.statistics.deletepanic', $this->id) . '" class="notification_delete btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
        return '';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute() {
        return $this->getShowButtonAttribute() .
                $this->getDeleteButtonAttribute();
    }

}
