<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 19/04/2018
 * Time: 18:43
 */

namespace pwbox\Controller\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class SessionMiddleware{

    public function __invoke(Request $request, Response $response, callable $next)
    {
        session_start();
        return $next($request, $response);
    }
}