<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use Validator;
use Illuminate\Http\Request;
use App\Services\UploadManager;

class UsersController extends Controller
{
    /**
     * @var UploadManager
     */
    private $manager;

    public function __construct(UploadManager $manager)
    {
        $this->manager = $manager;
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

    public function cropAvatar(Request $request)
    {
        $currentImage = $request->image;
        $data = $request->data;

        $image = Image::make($currentImage['relative_url']);

        $image->crop((int) $data['width'], (int) $data['height'], (int) $data['x'], (int) $data['y']);

        $image->save($currentImage['relative_url']);

        Auth::user()->saveAvatar($currentImage['url']);

        return response()->json($currentImage);
    }
}