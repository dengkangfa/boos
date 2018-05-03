<?php

namespace App\Services;


class Captcha
{
    public static function generate($mobile, $captcha, $code)
    {
        $key = 'captcha-'.str_random(15);

        $expiredAt = now()->addMinutes(2);
        \Cache::put($key, ['mobile' => $mobile, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'success' => false,
            'code' => $code,
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $result;
    }
}