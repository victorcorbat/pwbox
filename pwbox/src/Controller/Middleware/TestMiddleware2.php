<?php

namespace pwbox\Controller\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class TestMiddleware2{
    public function __invoke(Request $request, Response $response, callable $next){
        $response->getBody()->write('BEFORE2');
        $next($request, $response);
        $response->getBody()->write('AFTER2');
        return $response;
    }
}