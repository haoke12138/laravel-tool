<?php

namespace ZHK\Tool\Http;

use App\Http\Kernel as HttpKernel;
//use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

class Kernel extends HttpKernel
{
    protected $zhkMiddleware = [
        \ZHK\Tool\Http\Middleware\BaseSetting::class,
    ];

    protected $zhkMiddlewareGroups = [
        'web' => [
        ],

        'api' => [
        ],
    ];


    protected $zhkRouteMiddleware = [
        'login.verify' => \ZHK\Tool\Http\Middleware\CheckMemberToken::class,
        'login.status' => \ZHK\Tool\Http\Middleware\GetMemberToken::class,
    ];

    public function __construct(Application $app, Router $router)
    {
        parent::__construct($app, $router);
        $this->registerRouteMiddleware();
    }

    /**
     * 中间件注册
     */
    protected function registerRouteMiddleware()
    {
        foreach ($this->zhkMiddlewareGroups as $key => $middleware) {
            $this->router->middlewareGroup($key, array_merge($this->middlewareGroups[$key], $middleware, $this->zhkMiddleware));
        }

        foreach ($this->zhkRouteMiddleware as $key => $middleware) {
            $this->router->aliasMiddleware($key, $middleware);
        }
    }
}
