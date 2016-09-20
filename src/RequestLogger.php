<?php

namespace Chuck;

use Closure;
use Illuminate\Contracts\Foundation\Application;

class RequestLogger
{

    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);

        $time_elapsed_secs = microtime(true) - $start;

        $uri = $request->getRequestUri();
        $method = $request->getMethod();

        logger()->info('app.requests', [
            'req' => [
                'method' => $method,
                'url'    => $uri,
                'body'   => ($this->isAuthRoute($uri) && $method == "POST") ? 'Hidden For Auth Route' : $request->getContent()
            ],
            'res' => [
                'time'   => $time_elapsed_secs * 1000,
                'status' => $response->getStatusCode()
            ]
        ]);

        return $response;
    }

    /**
     * Check whether route is an authentication route
     *
     * @param $uri
     *
     * @return bool
     */
    public function isAuthRoute($uri)
    {
        return preg_match('/(auth|login)/', $uri) !== 0;
    }


}
