<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use Auth;
use DB;
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

    public function me(Request $request)
    {
        $user = Auth::user();

        return $this->respondWithItem($user, new UserTransformer);
    }

    public function avatar(Request $request)
    {
        $user = DB::table('users')->select('avatar')->where('mobile', $request->mobile)->first();

        if (is_null($user)) {
            return $this->noContent();
        }

        return $this->respondWithArray(['success' => true, 'avatar' => $user->avatar, 'code' => 0]);
    }
    
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'image'
        ]);
        
        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $path = 'avatars/' . Auth::user()->id;
        
        $result = $this->manager->store($request->file, $path);

        return $this->setStatusCode(201)
                ->respondWithArray(array_merge($result, ['success' => true, 'code' => 0]));
    }

    public function defaultAvatar(Request $request)
    {
        $user = Auth::user();
        $user->avatar = $request->avatar;
        $user->save();

        return $this->respondWithArray(['success' => true, 'url' => $user->avatar, 'code' => 0]);
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
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $result = $this->user->update(Auth::id(), $request->all());

        return $this->respondWithItem($result, new UserTransformer);
    }
}