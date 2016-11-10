<?php

namespace App\Http\Controllers\Backend\RescueOperation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Location\UpdateLocationRequest;
use App\Http\Requests\Backend\RescuerOperation\RescuerDetails;
use App\Repositories\Backend\RescueOperation\EloquentRescueOperationRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\RescueeOperation\RescueeOperation;
use App\Http\Requests\Backend\RescueeOperation\NotificationLists;
use App\Http\Requests\Backend\RescuerOperation\RescuerOperation;

class RescueOperationController extends Controller {

    /**
     * @var EloquentCompanyRepository
     */
    //private $newsfeedRepository;

    private $rescueOperationRepository;

    /**
     * AdminCompanyController constructor.
     * @param EloquentCompanyRepository $companyRepository
     */
    public function __construct(EloquentRescueOperationRepository $rescueOperationRepository) {

        $this->rescueOperationRepository = $rescueOperationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rescueeOperationActions(RescueeOperation $request) {
        $userdetails = $this->rescueOperationRepository->findActiveRescuers($request);  //find resquers within 5 KM
        return response()->json(['operation' => $userdetails]);
    }

    public function rescuerOperationDetails(RescuerDetails $request) {
        $details = $this->rescueOperationRepository->rescuerOperationDetails($request->active_rescuers_id);
        return response()->json(['operation' => $details]);
    }

    public function rescuerOperationResponse(RescuerOperation $request) {
        //save the resquer details once they accepted rescuee requests
        return response()->json(['rescue_operation' => $this->rescueOperationRepository->rescuersResponse($request)]);
    }

    public function rescueeForm() {
        return $this->rescueOperationRepository->rescueeForm();
    }

    public function rescuerLocationUpdates(UpdateLocationRequest $request) {
        if ($this->rescueOperationRepository->rescuerLocationUpdates($request)):
            return response()->json(['status' => 'location updated successfully']);
        else:
            return response()->json(['status' => 'location update failed please try again']);
        endif;
    }

    public function rescuerNotifications(NotificationLists $request) {
        if ($details = $this->rescueOperationRepository->rescuerNotifications($request)):
            return response()->json(['lists' => $details]);
        else:
            return response()->json(['status' => 'No Panic Signals']);
        endif;
    }

    public function notificationResponce(RescuerDetails $request) {
        $details = $this->rescueOperationRepository->rescuerOperationDetails($request->active_rescuers_id);
        $locations=json_decode($details->locations);
//        foreach($locations as $key =>$value){
//            if($key==$details->rescuee_id){
//                $details['address']=$value->addr;
//                $details['lat']=$value->lat;
//                $details['long']=$value->long;
//            }
//        }
       // unset($details->locations);
        $operation = $this->rescueOperationRepository->findOperation($request->active_rescuers_id);
        if (!empty($operation)) {
            if ($operation->rescuer_id == $request->rescuer_id)
                $details['accepted'] = 'Y'; //Current rescuer accepted
            else
                $details['accepted'] = 'N'; //another rescuer accepted
        } else
            $details['accepted'] = 'None'; //None of the rescuers accepted

        return $details;
    }

    public function latestNotification(NotificationLists $request) {
        if (count($details = $this->rescueOperationRepository->rescuerNotifications($request))>0):
            foreach ($details as $value) {
                if (!empty($operation = $this->rescueOperationRepository->findOperation($value->id))) {
                    if ($operation->rescuer_id == $request->user_id) {
                        $result = $this->rescueOperationRepository->rescuerOperationDetails($value->id); // getting the latest panic details
                        break;
                    } else
                        $result = "No Panic Signals Tagged";
                } else
                    $result = "No Panic Signals Tagged";
            }
            return response()->json(['result' => $result]);
        else:
            return response()->json(['result' => 'No Panic Signals']);
        endif;
    }
    public function rescueeOperationCancel(Request $request)
    {
        $this->rescueOperationRepository->rescueeOperationCancel($request);
        return response()->json(['result' => 'success']);
    }

}
