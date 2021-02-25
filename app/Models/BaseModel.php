<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
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
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that can be searched for.
     *
     * @var array
     */
    protected $searchable = [
        'id' => [
            'operator' => '='
        ]
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function getSearchable()
    {
        return $this->searchable;
    }

    public function newEloquentBuilder($query)
    {
        return new \App\Database\Eloquent\Builder($query);
    }

    public function hasRelation($relation)
    {
        return method_exists($this, $relation);
    }
}
