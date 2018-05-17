<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 27/04/2018
 * Time: 19:06
 */

namespace pwbox\Controller;

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class LoginController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response, array $args)
    {
        if(isset($_SESSION['id'])){
            if($_SESSION['id']!=''){

                return $response->withStatus(302)->withHeader('Location', '/dashboard');
            }
        }
        return $this->container->get('view')
            ->render($response, 'login.twig');

    }
    public function loginAction(Request $request, Response $response, array $args)
    {
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('login_user_service');
            if($service($data)){
                return $response->withStatus(302)->withHeader('Location', '/dashboard');
            };
        }catch(\Exception $e){
            $response = $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write($e->getMessage());
        }
        return $this->container->get('view')
            ->render($response, 'login.twig', ['error'=>'Usuario o contraseña incorrectos']);
    }

    public function logoutAction(Request $request, Response $response, array $args){
        $_SESSION['id']='';
        unset($_SESSION);
        return $response->withStatus(302)->withHeader('Location', '/');
    }
}


