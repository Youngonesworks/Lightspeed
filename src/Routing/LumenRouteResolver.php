<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Routing;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Controller as LumenController;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use YoungOnes\Lightspeed\Payload\RequestPayload;

class LumenRouteResolver
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
        $routeInfo = $this->findRoute();

        if (is_null($routeInfo)) {
            ray('no route????');
            return $this;
        }


        $this->request->setRouteResolver(static function () use ($routeInfo) {
            return $routeInfo;
        });

        ray($routeInfo);
        $action = $routeInfo['action'];

        if (isset($action['uses'])) {
            return $this->callControllerAction([true, $routeInfo['action'], []]);
        }

        return $this;

    }

    private function findRoute(): ?array
    {
        $router = app()->make(\Laravel\Lumen\Routing\Router::class);
        $method = $this->request->getMethod();
        $pathInfo = '/'.trim($this->request->getPathInfo(), '/');

        if (isset($router->getRoutes()[$method.$pathInfo])) {
            ray('route found');
            return $router->getRoutes()[$method.$pathInfo];
        }

        ray('No route found');
        return null;
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

    protected function callControllerAction($routeInfo)
    {
        $uses = $routeInfo[1]['uses'];

        if (is_string($uses) && ! Str::contains($uses, '@')) {
            $uses .= '@__invoke';
        }

        [$controller, $method] = explode('@', $uses);

        if (! method_exists($instance = app()->make($controller), $method)) {
            ray('Controller method not found');
            throw new NotFoundHttpException;
        }


            try {
                return $this->result = app()->call([$instance, $method], $routeInfo[2]);

            } catch (HttpResponseException $e) {
                return $e->getResponse();
            }

    }
}
