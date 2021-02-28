<?php

namespace App\Models;

use App\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * The attributes that are not mass assignable.
     */
    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that can be searched for.
     */
    protected array $searchable = [
        'id' => [
            'operator' => '='
        ]
    ];

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    public function getSearchable(): array
    {
        return $this->searchable;
    }

    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    public function hasRelation($relation): bool
    {
        return method_exists($this, $relation);
    }
}
