<?php

namespace Chuck;

use Illuminate\Support\ServiceProvider;

class RequestLoggerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->middleware(new RequestLogger($this->app));
    }
}
