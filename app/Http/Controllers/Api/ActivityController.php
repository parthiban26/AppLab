<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ActivityRepository;

class ActivityController extends Controller
{

    /**
     * ActivityRepository instance
     *
     */
    protected $activityRepository;    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ActivityRepository $activityRepository)
    {
    	$this->activityRepository = $activityRepository;
    }      

    /**
     * Post
     *
     * @return \Illuminate\Http\Response
     */
    public function post(Request $request)
    {   
        $inputs = $request->all();
        $response = $this->activityRepository->post($inputs);

        return $this->response($response);
    }       

    /**
     * Report
     *
     * @param Illuminate\Http\Request $request
     *
     * @return json $response
     */
    public function report(Request $request)
    {
        $inputs = $request->all();
        $response = $this->activityRepository->report($inputs);
        
        return $this->response($response);
    }     
}