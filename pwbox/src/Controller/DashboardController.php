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


class DashboardController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response, array $args)
    {
        //$user = $this->container->get('user repo')->findById($_SESSION['user_id']);
        $data['id'] = $_SESSION['id'];
        $service = $this->container->get('user_service');
        $user = $service($data);
        $menu['dashboard']=true;
        $menu['profile']=false;
        $menu['shared']=false;

        if(isset($args["folder_id"])){
            $data["id"] = $args["folder_id"];
            $id = $data["id"];
            $service = $this->container->get('folders_inside_service');
            $folders = $service($data);
            $service = $this->container->get('files_inside_service');
            $files = $service($data);
            return $this->container->get('view')
                ->render($response, 'dashboard.twig', ['folders'=>$folders, 'id'=>$id, 'files'=>$files, 'menu'=>$menu]);
        }else{
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

            return $this->container->get('view')
                ->render($response, 'dashboard.twig', ['folders'=>$folders, 'id'=>$id, 'files'=>$files, 'menu'=>$menu]); //obtener todas las carpetas compartidas y las que están dentro de la root.
        }
    }
}


