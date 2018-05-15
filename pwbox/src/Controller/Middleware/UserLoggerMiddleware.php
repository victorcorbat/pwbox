<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 19/04/2018
 * Time: 18:54
 */

namespace pwbox\Controller\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class UserLoggerMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if(!isset($_SESSION['user_id'])){
            return $response->withStatus(302)->withHeader('Location', '/');
        }
        else{
            $next($request, $response);
        }
    }
}