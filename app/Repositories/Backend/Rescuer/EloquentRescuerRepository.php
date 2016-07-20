<?php

namespace App\Repositories\Backend\Rescuer;

use App\Models\Rescuer;
use Illuminate\Http\Request;
use Auth;
use Storage;

class EloquentRescuerRepository {

    public function getDepartmentPaginated() {
        return Rescuer\Department::orderBy('id', 'DESC')->paginate(10);
    }

    public function save($request) {

        if ($request->has('id'))
            $obj = $this->find($request->id);
        else {
            $obj = new Rescuer\Department;
        }
        $obj->rescuertype_id = $request->type_id;
        $obj->department = $request->department;
        $obj->save();
    }
    
    public function rescuerType() {
        return Rescuer\RescuerType::all();
    }
    public function findDepartment($id) {
        return Rescuer\Department::find($id);
    }

    public function delete($id) {
        Rescuer\Department::where('id', $id)->delete();
    }

    public function departmentFiltering($id) {
       
        return Rescuer\Department::where('rescuertype_id', $id)->orderBy('id', 'DESC')->paginate(10);
    }

}
