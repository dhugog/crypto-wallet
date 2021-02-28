<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'credited_currency',
        'credited_amount',
        'debited_currency',
        'debited_amount'
    ];

    protected array $searchable = [
        'id' => [
            'operator' => '='
        ],
        'credited_currency' => [
            'operator' => '='
        ]
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
