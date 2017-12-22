<?php

namespace App\Http\Controllers\Auth;

use App\Http\Proxy\TokenProxy;
use Validator;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $proxy;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TokenProxy $proxy)
    {
        $this->middleware('guest')->except('logout');
        $this->proxy = $proxy;
    }

    public function login(Request $request, $driver) {
        $functionMap = ['verifycode' => 'verifyCodeLogin'];

        $function = $functionMap[$driver];
        if (!$function) {
            return response()->json([
                'success' => false,
                'message' => '不支持该方式登录',
                'code' => 10406
            ], 406);;
        }
        return $this->{$function}($request);
    }

    /**
     * 验证码方式登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCodeLogin(Request $request)
    {
        $validator = $this->validateLogin($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'code' => 10422
            ], 422);
        }

        $user = User::where('mobile', $request->mobile)->first();
        if (is_null($user)) {
            $user = User::create([
                'mobile' => $request->mobile,
                'password' => bcrypt(str_random(12).time())
            ]);
        }

        return $this->proxy->login($request->mobile, $user->getAuthPassword());
    }

    /**
     * 验证数据格式
     * @param Request $request
     * @return mixed
     */
    protected function validateLogin(Request $request)
    {
        return Validator::make($request->all(), [
            'mobile' => 'required|confirm_mobile_not_change|confirm_rule:mobile_required',
            'verifyCode' => 'required|verify_code',
        ],[
            'mobile.required' => '请填写手机号',
            'mobile.confirm_mobile_not_change' => '手机号码错误',
            'verifyCode.required' => '验证码不能为空',
            'verifyCode.verify_code' => '验证码不正确'
        ]);
    }
}
