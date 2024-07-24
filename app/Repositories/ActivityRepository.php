<?php

namespace App\Repositories;

use App\Repositories\Repository;
use Illuminate\Support\Facades\Validator;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityRepository extends Repository
{

    /**
     * UserActivity instance
     *
     */
    protected $userActivity;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserActivity $userActivity)
    {
        $this->userActivity = $userActivity;
    }      

    /**
     * Post
     *
     * @param array $inputs
     * @return json
     */
    public function post($inputs)
    {
        // Validate
        $validator = Validator::make($inputs, [
            'stepsCount' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
        ]);       

        if ($validator->fails()) {
            return $this->resultWithErrors($validator);
        } 

        $this->userActivity->user_id = Auth::user()->id;
        $this->userActivity->steps_count = $inputs['stepsCount'];
        $this->userActivity->start_time = new \MongoDB\BSON\UTCDateTime(new \DateTime($inputs['start_time']));
        $this->userActivity->end_time = new \MongoDB\BSON\UTCDateTime(new \DateTime($inputs['end_time']));
        if($this->userActivity->save()){
            return $this->result([
                'status'  => 'success',
                'message'   => 'User activity data stored successfully',
                'httpstatus' => 200
            ]);                
        }else{
            return $this->result([
                'status'  => 'error',
                'message'   => 'Error while storing user activity data',
                'httpstatus' => 400
            ]);                   
        }
    }
    
    /**
     * Report
     *
     * @param array $inputs
     * 
     * @return json
     */
    public function report($inputs)
    {
        $response = [];
        $response['topRanks'] = $this->userActivity->with('user')->where('start_time', '>=', Carbon::now()->startOfDay())->where('end_time', '<=', Carbon::now()->endOfDay())->orderBy('steps_count','desc')->limit(3)->get();
        $currentUserActivity = $this->userActivity->with('user')->select('user_id','steps_count')->where('user_id',Auth::user()->id)->where('start_time', '>=', Carbon::now()->startOfDay())->where('end_time', '<=', Carbon::now()->endOfDay())->first();
        $response['aboveActivities'] =  $this->userActivity->with('user')->whereNot('user_id',Auth::user()->id)->where('steps_count','>',$currentUserActivity->steps_count)->where('start_time', '>=', Carbon::now()->startOfDay())->where('end_time', '<=', Carbon::now()->endOfDay())->orderBy('steps_count','asc')->limit(3)->get();
        $response['belowActivities'] =  $this->userActivity->with('user')->whereNot('user_id',Auth::user()->id)->where('steps_count','<',$currentUserActivity->steps_count)->where('start_time', '>=', Carbon::now()->startOfDay())->where('end_time', '<=', Carbon::now()->endOfDay())->orderBy('steps_count','desc')->limit(3)->get();
        $response['currentUserActivity'] = $currentUserActivity;

        return $this->result([
            'status'  => 'success',
            'data' => $response,
            'httpstatus' => 200
        ]);        
    }    
}
