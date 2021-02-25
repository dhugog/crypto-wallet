<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'credited_currency',
        'credited_amount',
        'debited_currency',
        'debited_amount'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
