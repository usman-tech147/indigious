<?php

namespace App\Providers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('current_password', function($attribute, $value, $parameters, $validator){
            return Hash::check($value,auth()->guard('admin')->user()->password);
    },'Password does not match with current password!');
        Validator::extend('current_password_user', function($attribute, $value, $parameters, $validator){
            return Hash::check($value,auth()->guard('user')->user()->password);
        },'Password does not match with current password!');
        Validator::extend('current_password_user_api', function($attribute, $value, $parameters, $validator){
            return Hash::check($value,auth()->guard('user_api')->user()->password);
        },'Password does not match with current password!');
    }
}
