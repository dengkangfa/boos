<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use Auth;
use Image;
use Validator;
use Illuminate\Http\Request;
use App\Services\UploadManager;

class UsersController extends ApiController
{
    /**
     * @var UploadManager
     */
    private $manager;

    protected $user;

    public function __construct(UploadManager $manager, UserRepository $user)
    {
        parent::__construct();

        $this->manager = $manager;

        $this->user = $user;
    }
    
    public function avatar(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'image'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->getMessageBag()->first(),
                'code' => '10422'
            ], 422);
        }

        $path = 'avatars/' . Auth::user()->id;
        
        $result = $this->manager->store($request->file, $path);
        
        return response()->json($result, 201);
    }

    public function defaultAvatar(Request $request)
    {
        $user = Auth::user();
        $user->avatar = $request->avatar;
        $user->save();

        return $user->avatar;
    }

    public function cropAvatar(Request $request)
    {
        $currentImage = $request->image;
        $data = $request->data;

        $image = Image::make($currentImage['relative_url']);

        $image->crop((int) $data['width'], (int) $data['height'], (int) $data['x'], (int) $data['y']);

        $image->save($currentImage['relative_url']);

        Auth::user()->saveAvatar($currentImage['url']);

        $currentImage['url'] = $currentImage['url'] . '?';

        return response()->json($currentImage);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:12',
            'gender' => 'required',
            'job_date' => 'required',
            'birth_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->getMessageBag()->first(),
                'code' => 10422
            ], 422);
        }

        $result = $this->user->update(Auth::id(), $request->all());

        return $this->respondWithItem($result, new UserTransformer);
    }
}