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
            $id = $_SESSION["id"];
            $data["id"]=$id;
            //hay que obtener todos las carpetas y los archivos
            $service = $this->container->get('folders_user_service');
            $folder = $service($data);
            $id = $folder['id'];
            $data["id"] = $id;
            $service = $this->container->get('folders_inside_service');
            $folders = $service($data);
            $service = $this->container->get('files_inside_service');
            $files = $service($data);
            $menu['dashboard']=true;

            return $this->container->get('view')
                ->render($response, 'dashboard.twig', ['folders'=>$folders, 'id'=>$id, 'files'=>$files, 'menu'=>$menu]);
        }
        else{
            return $this->container->get('view')
                ->render($response, 'login.twig');
        }

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
        return $this->container->get('view')
            ->render($response, 'login.twig');
    }
}


