<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


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
    public function boot() {
        Validator::extend('custom_image_validation', function ($attribute, $value, $parameters, $validator) {

            if (filter_var($value, FILTER_VALIDATE_URL)) {
               
                $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif'];
                $urlPath = parse_url($value, PHP_URL_PATH);
                $extension = pathinfo($urlPath, PATHINFO_EXTENSION);
                return in_array(strtolower($extension), $allowedExtensions);
            } else {
                return $validator->validateAttribute($attribute, 'image|mimes:jpeg,png,jpg,gif|max:2048');
            }
        });
    }
    
}
