<?php

namespace App\Http\Controllers;

use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $request;

    protected function __construct(Request $request, BaseService $serviceInstance)
    {
        $this->request = $request;
        $this->serviceInstance = $serviceInstance;
    }

    public function index()
    {
        $this->validate($this->request, $this->rules(), $this->messages());

        $response = $this->serviceInstance->find(
            $this->request->except(['fields', 'sort', 'with']),
            isset($this->request->fields) && $this->request->fields ? explode(",", $this->request->fields) : null,
            isset($this->request->with) && $this->request->with ? explode(",", $this->request->with) : null,
            isset($this->request->sort) && $this->request->sort ? explode(",", $this->request->sort) : null
        );

        return response()->json($response);
    }

    public function show($id)
    {
        // if ($this->request->user())
        //     $this->authorize('get', $this->serviceInstance->getModel()->findOrFail($id));

        $data = $this->serviceInstance->get($id, $this->request->all());

        return response()->json($data);
    }

    public function store()
    {
        // $this->authorize('insert', $this->serviceInstance->getModel()->fill($this->request->all()));

        $this->validate($this->request, $this->rules(), $this->messages());

        $stored = $this->serviceInstance->create($this->request->all());

        return response()->json([
            'message' => "Created successfully!",
            'data' => $stored
        ], 201);
    }

    public function update($id)
    {
        // $this->authorize('update', $this->serviceInstance->getModel()->findOrFail($id));

        $this->validate($this->request, $this->rules($id));

        $obj = $this->serviceInstance->update($id, $this->request->all());

        return response()->json([
            'message' => "Updated successfully!",
            'data' => $obj
        ]);
    }

    public function destroy($id)
    {
        // $this->authorize('delete', $this->serviceInstance->getModel()->findOrFail($id));

        $this->serviceInstance->remove($id, $this->request->all());

        return response()->json([
            'message' => "Deleted successfully!"
        ]);
    }

    protected function rules($resourceId = null)
    {
        return [];
    }

    protected function messages()
    {
        return [];
    }
}
