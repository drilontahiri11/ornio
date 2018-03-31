<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
    const STATUS_PENDING=0;
    const STATUS_ACCEPTED=1;
    const STATUS_DENIED=2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='vacation_request';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_date', 'end_date', 'status','description','user_id'
    ];

    /**
     * @var array
     */
    protected $dates=[
        'start_date','end_date'
    ];

    /**
     * Save request made by Employee to DB
     * @param array $data
     * @return mixed
     */
    public static function saveVacationRequest($data=[])
    {
        $newRequest=new static();
        $newRequest->start_date=Carbon::createFromFormat('m-d-Y',$data['start_date']);
        $newRequest->end_date=Carbon::createFromFormat('m-d-Y',$data['end_date']);
        $newRequest->description=$data['description'];
        $newRequest->status=static::STATUS_PENDING;
        $newRequest->user_id=auth()->user()->id;
        return $newRequest->save();
    }


    /**
     * Get Employee Requests
     * @param $userId
     * @param int $take
     * @return mixed
     */
    public static function getVacationRequestForEmploye($userId,$take=0)
    {
        $result=static::where('user_id',$userId)->orderBy('created_at','DESC');

        if($take !=0){
            $result->take($take);
        }
        return $result->get()->toArray();
    }

    /**
     * Get Manager Requests
     * @param int $take
     * @return mixed
     */
    public static function getVacationRequestForManager($take=0)
    {
        $result=static::orderBy('created_at','DESC');

        if($take !=0){
            $result->take($take);
        }
        return $result->get()->toArray();
    }
}
