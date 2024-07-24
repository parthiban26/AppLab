<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Json response with status and response code
     *
     * @param array $result
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($result)
    {
        $httpstatus = $result['httpstatus'];
        unset($result['httpstatus']);

        $result = ['status' => $result['status']] + $result;

        return response()->json($result, $httpstatus);
    }    
}
