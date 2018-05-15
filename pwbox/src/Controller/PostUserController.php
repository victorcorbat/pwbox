<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 12/04/2018
 * Time: 19:30
 */

namespace pwbox\Controller;

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class PostUserController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response){
        $messages = $this->container->get('flash')->getMessages();
        $userRegisteredMessages = isset($messages['user_register']) ? $messages['user_register'] : [];
        return $this->container->get('view')
            ->render($response, 'register.twig', [
                'message'=>$userRegisteredMessages,
            ]);
    }

    public function registerAction(Request $request, Response $response)
    {
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('post_user_use_case'); //Clase PostUserUseCase
            $service($data);
            $this->container->get('flash')->addMessage('user_register', 'User successfully registered');
            return $response->withStatus(302)->withHeader('Location', '/user');

        }catch(\Exception $e){
            return $this->container->get('view')
                ->render($response, 'register.twig', [
                    'error'=>$e->getMessage(),
            ]);
        }

        return $response;
    }
}