<?php
namespace App\Models\RescueOperation\Traits\Attribute;
use App\Models\Access\User\User;

/**
 * Class UserAttribute
 * @package App\Models\Access\User\Traits\Attribute
 */
trait OperationAttribute
{
    public function getRescueeNameAttribute(){
        if ( is_null($this->rescuee_id) ) {
            return null;
        }
        return User::where('id', $this->rescuee_id)->value('firstname');
    }

    public function getRescuerNameAttribute(){
        if ( is_null($this->rescuer_id) ) {
            return null;
        }
        return User::where('id', $this->rescuer_id)->value('firstname');
    }

    public function getOperationStatusAttribute(){
        if ( is_null($this->finished_at) ) {
            return 'not finished';
        }
        return 'finished';
    }
}
