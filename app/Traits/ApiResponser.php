<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    function successResponse($data, $code = 200)
    {
         return response()->json($data, $code); 
    }
    
    function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    function showAll($collection, $code = 200)
    {
        if($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }
        
        $collection = $this->paginateCollection($collection);
        $transformer = $collection->first()->transformer;
        $collection = $this->transformData($collection, $transformer);

        return $this->successResponse(['data' => $collection], $code);
    }
   

    function showOne(Model $instance, $code = 200)
    {
       $transformer = $instance->transformer;
       $instance = $this->transformData($instance, $transformer);

        return $this->successResponse(['data' => $instance], $code);
    }

    function showMessage($message, $code = 200)
    {
        return $this->successResponse($message, $code);
    }

    function paginateCollection(Collection $collection)
    {
        $rules = [
			'por_pagina' => 'integer|min:2|max:50'
		];
        $message = [
            'integer' => 'EL campo :attribute debe de ser un numero entero',
            'min' => 'EL campo :attribute debe de ser un numero mayor o igual que dos',
            'max' => 'EL campo :attribute debe de ser un numero menor o igual que cincuenta',
        ];

        Validator::validate(request()->all(), $rules,$message);
        
        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 20;
        if (request()->has('por_pagina')) {
			$perPage = (int) request()->por_pagina;
		}

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
			'path' => LengthAwarePaginator::resolveCurrentPath(),
		]);

        $paginated->appends(request()->all());
        return $paginated;
    }

    protected function transformData($data, $transformer)
	{
		$transformation = fractal($data, new $transformer);

		return $transformation->toArray();
	}
    
}