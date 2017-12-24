<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Toplan\Sms\SmsManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(SmsManager $smsManager)
    {
        $smsManager->storeRule('mobile', 'mobile_exists', 'required|zh_mobile|exists:users,mobile');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
