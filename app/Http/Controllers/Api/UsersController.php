<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use Auth;
use DB;
use Image;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;
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
        $this->middleware('auth', ['except' => ['avatar']]);

        parent::__construct();

        $this->manager = $manager;

        $this->user = $user;
    }

    public function me(Request $request)
    {
        $user = Auth::user();

        return $this->respondWithItem($user, new UserTransformer);
    }

    public function updateField(Request $request)
    {
        $result = $this->user->update(Auth::id(), $request->all());

        return $this->respondWithItem($result, new UserTransformer);
    }

    /**
     * 通过手机号码查找该手机号码用户的头像
     *
     * @param Request $request
     * @return response|\Illuminate\Http\JsonResponse
     */
    public function avatar(Request $request, User $user)
    {
        $user = $user->getAvatarByMobile($request->mobile);

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

    public function updateProfile(UserRequest $request)
    {
//        $validator = Validator::make($request->all(), [
//            'name' => 'required|max:12',
//            'gender' => 'required',
//            'job_date' => 'required',
//            'birth_date' => 'required|date'
//        ]);
//
//        if ($validator->fails()) {
//            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
//        }

        $result = $this->user->update(Auth::id(), $request->all());

        return $this->respondWithItem($result, new UserTransformer);
    }

    public function updateAdvantage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'advantage' => 'required|max:140',
        ]);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $this->user->updateColumn(Auth::id(), $request->all());

        return $this->respondWithArray(['advantage' => $request->advantage, 'success' => true, 'code' => 0]);
    }

    public function updateHomepage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'homepages' => 'required|max:185',
        ]);

        if ($validator->fails()) {
            return $this->errorUnprocessableEntity($validator->getMessageBag()->first());
        }

        $this->user->updateColumn(Auth::id(), $request->all());

        return $this->respondWithArray(['homepages' => array_slice(explode('・', $request->homepages), 0, 3), 'success' => true, 'code' => 0]);
    }

    public function updateRole(Request $request)
    {
        if (!in_array($request->role, ['recruiter', 'applicant'])) {
            return $this->errorUnprocessableEntity();
        }

        $user = \Auth::user();
        $user->syncRoles($request->role);
        return $this->respondWithArray(['roles' => $user->getRoleNames(), 'success' => true, 'code' => 0]);
    }

    public function contacts()
    {
        $user = \Auth::user();
        return $this->respondWithCollection($user->contacts()->with('company')->get(), new UserTransformer(true));
    }
}