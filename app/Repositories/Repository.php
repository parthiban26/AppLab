<?php

namespace App\Repositories;

class Repository
{
    /**
     * Result with status code
     *
     * @param array $result
     * @return array $result
     */
    public function result(array $result)
    {
        if(!isset($result['status'])) {
            $result['status']   = 'success';
        }
        if(!isset($result['httpstatus'])) {
            $result['httpstatus']   = 200;
        }
        return $result;
    }

    /**
     * Result with errors
     *
     * @param \Illuminate\Validation\Validator $validator
     * @param int $httpstatus = 400
     * @return array $data
     */
    public function resultWithErrors($validator, $httpstatus = 400)
    {
        $messages = $validator->messages()->get('*');
        $errors = [];
        foreach($messages as $key => $message) {
            $errors[$key] = is_array($message) ? $message[0] : $message;
        }
        return $this->result([
            'status'    => 'error',
            'message'    => $errors,
            'httpstatus'    => $httpstatus,
        ]);
    }
}
