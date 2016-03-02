<?php

namespace Chuck;

use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    public function register()
    {
        print 'REGISTERING REQUEST LOGGER MIDDLEWARE';
        $this->app->middleware(new RequestLogger($this->app));
    }
}
