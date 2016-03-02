<?php

namespace Chuck;

use \Symfony\Component\HttpKernel\HttpKernelInterface;
use \Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestLogger implements HttpKernelInterface
{

    /**
     * The wrapped kernel implementation.
     *
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected $app;

    /**
     * Create a new RequestLogger instance.
     *
     * @param  \Symfony\Component\HttpKernel\HttpKernelInterface  $app
     * @return void
     */
    public function __construct(HttpKernelInterface $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the given request, get the response and log information about it.
     *
     * @implements HttpKernelInterface::handle
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  int   $type
     * @param  bool  $catch
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(SymfonyRequest $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        // Handle on passed down request
        $start = microtime(true);
        $response = $this->app->handle($request, $type, $catch);
        $time_elapsed_secs = microtime(true) - $start;

        $uri = $request->getRequestUri();
        $method = $request->getMethod();

        \Log::info('app.requests', [
            'req' => [
                'method' => $method,
                'url' => $uri,
                'body' => (!$this->isAuthRoute($uri) && $method == "POST") ? $request->getContent() : 'Hidden For Auth Route'
            ],
            'res' => [
                'time' => $time_elapsed_secs,
                'status' => $response->getStatusCode()
            ]
        ]);

        return $response;
    }

    public function isAuthRoute($uri)
    {
        return preg_match('/(auth|login)/', $uri) !== 0;
    }


}
