<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AuthRepository;

class AuthController extends Controller
{

    /**
     * AuthRepository instance
     *
     */
    protected $authRepository;    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $authRepository)
    {
    	$this->authRepository = $authRepository;
    }      

    /**
     * login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {   
        $inputs = $request->all();
        $response = $this->authRepository->login($inputs);

        return $this->response($response);
    }       

    /**
     * Auth User
     *
     * @param Illuminate\Http\Request $request
     *
     * @return json $response
     */
    public function user(Request $request)
    {
        $inputs = $request->all();
        $response = $this->authRepository->user($inputs);
        
        return $this->response($response);
    }     
}