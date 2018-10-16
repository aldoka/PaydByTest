<?php

namespace App\Providers;

use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\ Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('is_base64_image',function($attribute, $value, $params, $validator) {
            $mimeType = '';
            try {
                if ($value instanceof UploadedFile) {
                    /** @var UploadedFile $value*/
                    $mimeType = $value->getMimeType();
                } else if (is_string($value)) {
                    $image = base64_decode($value);
                    $f = finfo_open();
                    $mimeType = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
                } else {
                    throw new ValidationHttpException();
                }
            } catch (\Throwable $t) {
                throw new ValidationHttpException('Application can\'t validate the image.');
            }

            return strncmp($mimeType, 'image/', strlen('image/')) === 0;
        });
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
