<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{   
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'duration', 
        'ip_address', 
        'url', 
        'method', 
        'headers', 
        'input', 
        'output', 
        'http_response_code',
        'user_id'
    ];
}
