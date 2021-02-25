<?php

namespace App\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder extends EloquentBuilder
{
    public function search($params = [])
    {
        $query = $this;

        foreach ($params as $field => $value) {
            $modelSearchables = $this->model->getSearchable();

            if (!isset($modelSearchables[$field])) continue;

            $fieldOperator = $modelSearchables[$field]['operator'];
            $postWildcard = isset($modelSearchables[$field]['post_wildcard']) ? $modelSearchables[$field]['post_wildcard'] : '';
            $preWildcard = isset($modelSearchables[$field]['pre_wildcard']) ? $modelSearchables[$field]['pre_wildcard'] : '';

            $query = $value ? $query->where($this->model->getTable() . '.' . $field, $fieldOperator, $preWildcard . $value . $postWildcard) : $query->whereNull($field);
        }

        return $query;
    }
}
