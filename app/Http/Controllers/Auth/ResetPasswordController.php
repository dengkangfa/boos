<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Validator;
use Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset(Request $request)
    {
        $validator = $this->validateReset($request, $this->rules(), $this->validationErrorMessages());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'code' => 10422
            ], 422);
        }


        $user = User::where('mobile', $request->mobile)->first();

        $this->resetPassword($user, $request->password);

        return response()->json([
            'success' => true,
            'message' => '密码重置成功',
            'code' => 0
        ],200);
    }

    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->save();

        event(new PasswordReset($user));
    }

    protected function rules()
    {
        return [
            'mobile' => 'required|zh_mobile',
            'password' => 'required|min:6',
            'verifyCode' => 'required|verify_code'
        ];
    }

    protected function validationErrorMessages()
    {
        return [
          'mobile.required' => '手机号不能为空',
          'mobile.zh_mobile' => '输入号码与归属地不匹配',
          'verifyCode.required' => '验证码不能为空',
          'verifyCode.verify_code' => '验证码不正确'
        ];
    }

    protected function validateReset(Request $request, array $rules, array $messages = [])
    {
        return Validator::make($request->all(), $rules, $messages);
    }
}
