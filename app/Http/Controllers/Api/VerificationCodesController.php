<?php

namespace App\Http\Controllers\Api;

use App\Services\Captcha;
use Carbon\Carbon;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Http\Request;
use Toplan\Sms\SmsManager;

class VerificationCodesController extends ApiController
{
    protected $manager;

    protected $builder;

    public function __construct(SmsManager $manager, CaptchaBuilder $builder)
    {
        $this->manager = $manager;

        $this->builder = $builder;
    }

    public function store(Request $request)
    {
        if ($request->captcha_code) {
            $captchaData = \Cache::get($request->captcha_key);

            if (!$captchaData) {
                return $this->setStatusCode(422)->respondWithError('图片验证码已失效', 10004);
            }

            if (!hash_equals($captchaData['code'], $request->captcha_code)) {
                // 验证错误就清除缓存
                \Cache::forget($request->captcha_key);
                return $this->generateCaptcha(10006);
            }

            $mobile = $captchaData['mobile'];

            $key = $mobile . '_send_code_count';
        } else {
            $mobile = $request->mobile;
            $key = $mobile . '_send_code_count';
            $count = \Cache::get($key, 0);
            if ($count >= 3) {
                return $this->generateCaptcha();
            }
        }

        $res = $this->manager->validateSendable();
        if (!$res['success']) {
            return response()->json($res);
        }

        $res = $this->manager->validateFields();
        if (!$res['success']) {
            return response()->json($res);
        }

        $result = $this->manager->requestVerifySms();

        if ($result['success']) {
            if (\Cache::has($key)) {
                \Cache::increment($key);
            } else {
                \Cache::put($key, 1, Carbon::now()->addDay());
            }
        }
        return $this->setStatusCode(201)->respondWithArray($result);
    }

    public function generateCaptcha($code = 10005)
    {
        $phraseBuilder = new PhraseBuilder(4);
        $captcha = new CaptchaBuilder(null, $phraseBuilder);
        return Captcha::generate(request('mobile'), $captcha->setMaxBehindLines(5)->setMaxFrontLines(10)->build(), $code);
    }
}
