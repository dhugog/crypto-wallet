<?php

namespace App\Services;

use App\Models\BaseModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseService
{
    protected $modelInstance;

    public function __construct(BaseModel $model)
    {
        $this->modelInstance = $model;
    }

    public function find(array $params, $fields = null, $with = null, $sort = null)
    {
        $query = $this->modelInstance;

        if (sizeof($params) > 0)
            $query = $query->search($params);

        if ($fields)
            $query = $query->select($fields);

        if ($with)
            $query = $query->with($with);

        if ($sort)
            foreach ($sort as $sort)
                $query->orderBy(substr($sort, 1), substr($sort, 0, 1) === '-' ? 'DESC' : 'ASC');

        $data = $query->get();

        return $data;
    }

    public function get($id, array $params = null)
    {
        $query = $this->modelInstance;

        if (isset($params['with']))
            $query = $query->with(explode(",", $params['with']));

        if ($params && count($params) > 0)
            $query = $query->search($params);

        $data = $query->whereId($id)->first();

        if (!$data)
            throw new NotFoundHttpException("Recurso não encontrado.");

        return $data;
    }

    public function create(array $params)
    {
        $stored = $this->modelInstance->create($params);

        $arrayParams = array_filter($params, fn ($param) => is_array($param));

        foreach ($arrayParams as $key => $obj)
            if ($stored->hasRelation($key))
                $stored->{$key}()->save($stored->{$key}()->getRelated()->fill($obj));

        return $stored;
    }

    public function update($id, array $params)
    {
        $obj = $this->get($id, $params);

        $obj->update($params);

        return $obj;
    }

    public function remove($id, array $params)
    {
        $obj = $this->get($id, $params);

        $response = $obj->delete();

        return $response;
    }

    public function getModel()
    {
        return $this->modelInstance;
    }
}
