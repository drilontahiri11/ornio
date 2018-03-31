<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VacationDays extends Model
{
    const MAX_DAYS=20;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='vacation_days';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','year','days'
    ];

    /**
     * Save Used Vacation Days For Employe
     * @param $request
     */
    public static function saveDaysForEmployee($request)
    {
        $daysUsed=$request->start_date->diffInDays($request->end_date);
        $currentYear=date("Y");

        $existingRecord=static::where('user_id',$request->user_id)->where('year',$currentYear)->first();
        if(!is_null($existingRecord)){
            $used=$existingRecord->days;
            $daysUsed +=$used;
            $existingRecord->days=$daysUsed;
            $existingRecord->save();
        }else{
            $newRecord=new static();
            $newRecord->days=$daysUsed;
            $newRecord->user_id=$request->user_id;
            $newRecord->year=$currentYear;
            $newRecord->save();
        }
    }

    public static function getUsedVacationDaysForEmployee()
    {
        $currentYear=date("Y");
        return static::where('user_id',auth()->user()->id)->where('year',$currentYear)->first();
    }

}
