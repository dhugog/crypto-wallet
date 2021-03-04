<?php

namespace App\Models;

class Currency extends BaseModel
{
    protected $primaryKey = 'code';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'crypto',
        'int_unit_multiplier',
        'price_api_url'
    ];
}
