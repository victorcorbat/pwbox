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


class SharedController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response, array $args)
    {

        $menu['dashboard']=false;
        $menu['profile']=false;
        $menu['shared']=true;

        if(isset($args["folder_id"])){
            $data["id"] = $args["folder_id"];
            $id = $data["id"];
            $service = $this->container->get('folders_inside_service');
            $folders = $service($data);
            $service = $this->container->get('files_inside_service');
            $files = $service($data);
            return $this->container->get('view')
                ->render($response, 'dashboard.twig', ['folders'=>$folders, 'id'=>$id, 'files'=>$files, 'menu'=>$menu]);
        }
        else{
            $id = $_SESSION['id'];
            $data['id'] = $id;
            $service = $this->container->get('shared_folder_service');
            $folders = $service($data);

            return $this->container->get('view')
                ->render($response, 'dashboard.twig', ['folders'=>$folders, 'id'=>$id, 'menu'=>$menu]); //obtener todas las carpetas compartidas y las que están dentro de la root.
        }
    }
}


