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
    private Router $router;
    private Request $request;
    private $result;

    private function __construct(Request $request)
    {
        $this->router  = app()->make(Router::class);
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
        $route = $this->findRoute();

        $this->request->setRouteResolver(static function () use ($route) {
            return $route;
        });

        $this->result = $route->run();

        return $this;
    }

    private function findRoute(): Route
    {
        $route = $this->router->getRoutes()->match($this->request);
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
