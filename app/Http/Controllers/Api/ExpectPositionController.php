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
//        if (\Auth::user()->expectPosition()->count()) {
//            return $this->errorForbidden();
//        }
        $expectPosition->fill($request->all());
        $expectPosition->user_id = \Auth::id();
        $expectPosition->save();

        return $this->setStatusCode(201)->respondWithItem($expectPosition, new ExpectPositionsTransformer());
    }

    public function update(ExpectPositionRequest $request, ExpectPosition $expectPosition)
    {
        $expectPosition->update($request->all());

        return $this->respondWithItem($expectPosition, new ExpectPositionsTransformer());
    }

    public function destroy(ExpectPosition $expectPosition)
    {
        $expectPosition->delete();

        return $this->noContent();
    }

    public function currentUserAllExpectPosition()
    {
        $expectPositions = \Auth::user()->expectPositions;

        return $this->respondWithCollection($expectPositions, new ExpectPositionsTransformer());
    }

    public function whetherToRepeat(ExpectPosition $expectPosition)
    {
        $where[] = ['position_name', request('position_name')];
        $where[] = ['location_name', request('location_name')];
        $where[] = ['id', '!=', request('id')];

        return $this->respondWithArray(['exists' => \Auth::user()->expectPositions()->where($where)->exists()]);
    }
}
