<?php namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\VacationRequest;
use Illuminate\Http\Request;
use App\User;
use Validator;

class VacationController extends Controller
{
    /**
     * Based on User role here you can view all Vacantions Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user=auth()->user();
        if($user->role_id==User::ROLE_ADIMN){
            $requests=VacationRequest::getVacationRequestForManager();
            return response()->json($requests);
        }else if($user->role_id==User::ROLE_EMPLOYEE){
            $requests=VacationRequest::getVacationRequestForEmploye($user->id);
            return response()->json($requests);
        }
    }

    /**
     * Employee can create a vacation request
     */
    public function newVacationRequest(Request $request)
    {
        $data = $request->only('start_date', 'end_date','description');
        $rules = [
            'start_date' => 'required|date|date_format:m-d-Y',
            'end_date' => 'required|date|date_format:m-d-Y',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }
        if(VacationRequest::saveVacationRequest($data)){
            //TODO::EVENT/EMAIL FOR ADMIN
            return response()->json(['success'=> true, 'message'=> 'Your vacation request has been sent to Manager.']);
        }else{
            return response()->json(['success'=> false, 'error'=> 'Something went wrong!']);
        }
    }

    /**
     * This method will accept employee request by Manager
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptRequest($id)
    {
        $request=VacationRequest::find($id);
        if(!is_null($request)){
            $request->status=VacationRequest::STATUS_ACCEPTED;
            $request->save();
            //TODO::EVENT/EMAIL FOR EMPLOYE
            return response()->json(['success'=> true, 'message'=> 'Vacation Request Accepted.']);
        }else{
            return response()->json(['success'=> false, 'error'=> 'Record Not Found!']);
        }
    }

    /**
     * This method will deny employee request by Manager
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function denyRequest($id)
    {
        $request=VacationRequest::find($id);
        if(!is_null($request)){
            $request->status=VacationRequest::STATUS_DENIED;
            $request->save();
            //TODO::EVENT/EMAIL FOR EMPLOYE
            return response()->json(['success'=> true, 'message'=> 'Vacation Request Denied.']);
        }else{
            return response()->json(['success'=> false, 'error'=> 'Record Not Found!']);
        }
    }


}