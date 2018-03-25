<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ExpectPositionRequest;
use App\Models\ExpectPosition;
use App\Transformers\ExpectPositionsTransformer;
use Illuminate\Http\Request;
use Validator;

class ExpectPositionController extends ApiController
{
    public function store(ExpectPositionRequest $request, ExpectPosition $expectPosition)
    {
        if (\Auth::user()->expectPosition()->count()) {
            return $this->errorForbidden();
        }
        $expectPosition->fill($request->all());
        $expectPosition->user_id = \Auth::id();
        $expectPosition->save();

        return $this->setStatusCode(201)->respondWithItem($expectPosition, new ExpectPositionsTransformer());
    }
}
