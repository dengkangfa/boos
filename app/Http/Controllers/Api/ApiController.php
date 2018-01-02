<?php

namespace App\Http\Controllers\Api;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $statusCode = 200;

    protected $success = true;

    protected $code = 0;

    protected $fractal;

    public function __construct()
    {
        $this->fractal = new Manager;
    }

    /**
     * Repond a no content response.
     *
     * @return response
     */
    public function noContent()
    {
        return response()->json(null, 204);
    }

    public function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    public function respondWithArray(array $array, array $header = [])
    {
        return response()->json(array_merge($array, ['success' => $this->success, 'code' => $this->code]), $this->statusCode, $header);
    }

    /**
     * 不可处理的实体（422）错误反馈
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorUnprocessableEntity($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'code' => 10422
        ], 422);
    }
}