<?php

namespace App\Http\Controllers\Api;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $statusCode = 200;

    protected $fractal;

    public function __construct()
    {
        $this->fractal = new Manager;
    }

    public function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    public function respondWithArray(array $array, array $header = [])
    {
        return response()->json($array, $this->statusCode, $header);
    }
}