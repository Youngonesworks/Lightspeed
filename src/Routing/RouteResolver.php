<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Routing;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use YoungOnes\Lightspeed\Payload\RequestPayload;

class RouteResolver
{
    private Request $request;
    private $result;

    private function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function resolve(RequestPayload $payload): self
    {
        $request = SymfonyRequest::create($payload->getUri(), $payload->getMethod(), $payload->parameters()->all());
        $request = Request::createFromBase($request);

        return new static($request);
    }

    public function run(): self
    {
        if (class_exists('\Laravel\Lumen\Routing\Router')) {
            $router = app()->make(\Laravel\Lumen\Routing\Router::class);
            $method = $this->request->getMethod();
            $pathInfo = '/'.trim($this->request->getPathInfo(), '/');
            ray($method.$pathInfo);
            ray($router->getRoutes());
            if (isset($router->getRoutes()[$method.$pathInfo])) {
                $routeInfo = $router->getRoutes()[$method.$pathInfo];
                $this->request->setRouteResolver(static function () use ($routeInfo) {
                    return $routeInfo;
                });

                $action = $routeInfo[1];

                if (isset($action['uses'])) {
                    return $this->prepareResponse($this->callControllerAction($routeInfo));
                }

                ray('route found');
            }

            return $this;
        }

        $route = $this->findRoute();

        $this->request->setRouteResolver(static function () use ($route) {
            return $route;
        });

        $this->result = $route->run();

        return $this;
    }

    private function findRoute(): Route
    {
        $route = app()->make(Router::class)->getRoutes()->match($this->request);
        event(new RouteMatched($route, $this->request));

        return $route;
    }

    public function toResponse(): JsonResponse
    {
        $response = new JsonResponse($this->result);

        if ($response->getStatusCode() === Response::HTTP_NOT_MODIFIED) {
            $response->setNotModified();
        }

        return $response->prepare($this->request);
    }

    public function getResult(): Request
    {
        return $this->request;
    }
}
