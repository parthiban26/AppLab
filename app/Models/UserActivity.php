<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class UserActivity extends Model
{

 	protected $connection = 'app_db';
    protected $collection = 'user_activities';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        '_id',
        'user_id',
        'start_time',
        'end_time',
        'created_at',
        'updated_at',
    ];    
    
    /**
     * Get the user record associated with the activity.
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }     
}
