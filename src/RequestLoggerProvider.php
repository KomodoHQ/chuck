<?php

namespace Chuck;

use Illuminate\Support\ServiceProvider;

class RequestLoggerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\Chuck\RequestLogger::class, function ($app) {
            return new \Chuck\RequestLogger($app);
        });
    }
}
