<?php namespace app\Http\Controllers;


use App\User;
use App\VacationRequest;

class DashboardController
{
    public function index()
    {
        $user=auth()->user();
        if($user->role_id==User::ROLE_ADIMN){
            $requests=VacationRequest::getVacationRequestForManager(3);
            return response()->json($requests);
        }else if($user->role_id==User::ROLE_EMPLOYEE){
            $requests=VacationRequest::getVacationRequestForEmploye($user->id,3);
            return response()->json($requests);
        }
    }
}