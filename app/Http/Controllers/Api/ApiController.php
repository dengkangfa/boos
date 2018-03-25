<?php

namespace App\Http\Controllers\Api;

use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use App\Http\Controllers\Controller;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\ArraySerializer;

class ApiController extends Controller
{
    protected $statusCode = 200;

    const CODE_NOT_FOUND = 10404;
    const CODE_NOT_ACCEPTABLE = 10406;
    const CODE_UNPROCESSABLE_ENTITY = 10422;
    const CODE_FORBIDDEN = '10403';

    protected $code = 0;

    protected $fractal;

    public function __construct()
    {
        $this->fractal = new Manager;
//        $this->fractal->setSerializer(new ArraySerializer());

        if (isset($_GET['include'])) {
            $this->fractal->parseIncludes($_GET['include']);
        }
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getCode()
    {
        return $this->code;
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

        return $this->respondWithArray(array_merge($rootScope->toArray(), ['success' => true, 'code' => $this->code]));
    }

    /**
     * Respond the collection data.
     *
     * @param $collection
     * @param $callback
     * @return mixed
     */
    public function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray(array_merge($rootScope->toArray(), ['success' => true, 'code' => $this->code]));
    }

    /**
     *  Respond the collection data with pagination.
     *
     * @param $paginator
     * @param $callback
     * @return mixed
     */
    public function respondWithPaginator($paginator, $callback)
    {
        $resource = new Collection($paginator->getCollection(), $callback);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray(array_merge($rootScope->toArray(), ['success' => true, 'code' => $this->code]));
    }

    public function respondWithArray(array $array, array $header = [])
    {
        return response()->json($array, $this->statusCode, $header);
    }

    /**
     * Respond the error message.
     *
     * @param  string $message
     * @param  string $errorCode
     * @return json
     */
    protected function respondWithError($message, $errorCode)
    {
        if ($this->statusCode === 200) {
            trigger_error(
                "You better have a really good reason for erroring on a 200...",
                E_USER_WARNING
            );
        }

        return $this->respondWithArray([
            'success' => false,
            'message' => $message,
            'code' => $errorCode
        ]);
    }

    /**
     * Respond the error of 'Forbidden'
     *
     * @param  string $message
     * @return json
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * 不可处理的实体（422）错误反馈
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorUnprocessableEntity($message = 'Unprocessable Entity')
    {
        return $this->setStatusCode(422)
                    ->respondWithError($message, self::CODE_UNPROCESSABLE_ENTITY);
    }

    /**
     * Respond the error of 'Resource Not Found'
     *
     * @param string $message
     * @return mixed
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)
            ->respondWithError($message, self::CODE_NOT_FOUND);
    }

    public function errorNotAcceptable($message = 'Not Acceptable')
    {
        return $this->setStatusCode(406)
            ->respondWithError($message, self::CODE_NOT_ACCEPTABLE);
    }

}