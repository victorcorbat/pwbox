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


class ShareController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function shareAction(Request $request, Response $response, array $args)
    {
        $data["folder_id"] = $args["folder_id"];
        $data["user_email"] = $_POST['email'];
        $service = $this->container->get('share_service');
        $success = $service($data);
        $menu['dashboard']=true;

        $id = $args["folder_id"];

        $data["id"] = $args["folder_id"];

        $service = $this->container->get('folder_name_service');
        $title = $service($data);

        $menu['dashboard']=true;
        $menu['profile']=false;
        $menu['shared']=false;

        $data['folder']=$id;
        $service = $this->container->get('get_parent_service');
        $parent = $service($data);

        //mirar si al root puedo acceder
        $service = $this->container->get('folders_inside_service');
        $folders = $service($data);
        $service = $this->container->get('files_inside_service');
        $files = $service($data);
        return $this->container->get('view')
            ->render($response, 'dashboard.twig', ['folders'=>$folders, 'id'=>$id, 'title'=>$title, 'parent'=>$parent, 'files'=>$files, 'menu'=>$menu, 'success'=>$success]);


    }
}


