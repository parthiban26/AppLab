<?php

namespace App\Repositories;

use App\Repositories\Repository;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthRepository extends Repository
{

    /**
     * User instance
     *
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }      

    /**
     * Login
     *
     * @param array $inputs
     * @return json
     */
    public function login($inputs)
    {
        // Validate
        $validator = Validator::make($inputs, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);       

        if ($validator->fails()) {
            return $this->resultWithErrors($validator);
        } 

        $user = $this->user->where('email',$inputs['email'])->first();
        if(!$user){
            return $this->result([
                'status'  => 'error',
                'message' => 'Email address not found',
                'httpstatus' => 400
            ]);       
        }else{
            if(Hash::check($inputs['password'], $user->password)) {
                return $this->result([
                    'status'  => 'success',
                    'data'   => $user,
                    'token'   => $user->createToken('App')->plainTextToken,
                    'httpstatus' => 200
                ]);                
            }else{
                return $this->result([
                    'status'  => 'error',
                    'message'   => 'Invalid password',
                    'httpstatus' => 400
                ]);                   
            }
        }
    }
    
    /**
     * Auth user
     *
     * @param array $inputs
     * 
     * @return json
     */
    public function user($inputs)
    {
        return $this->result([
            'status'  => 'success',
            'data' => Auth::user(),
            'httpstatus' => 200
        ]);        
    }    
}
