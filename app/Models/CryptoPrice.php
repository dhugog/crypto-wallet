<?php

namespace App\Models;

class CryptoPrice extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'cryptocurrency',
        'currency',
        'buy',
        'sell'
    ];

    protected $hidden = [
        'id',
        'cryptocurrency',
        'currency',
        'updated_at'
    ];

    protected $casts = [
        'buy' => 'float',
        'sell' => 'float'
    ];
}
