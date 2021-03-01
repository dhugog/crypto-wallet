<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $hidden = [
        'user_id',
        'updated_at'
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
