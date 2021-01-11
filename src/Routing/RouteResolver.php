<?php


namespace YoungOnes\Lightspeed\Routing;


use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;

class RouteResolver
{
    private Router $router;

    public function __construct()
    {
        /** @var Router $router */
        $this->router = app()->make(Router::class);
    }
}
