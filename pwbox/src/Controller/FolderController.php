<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 11/04/2018
 * Time: 20:27
 */

namespace pwbox\Controller;

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class FolderController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function createAction(Request $request, Response $response, array $args)
    {
        $foldername = $_POST["nombre"];

        if(strlen($foldername)>40){
            $foldername = substr($foldername, 0, 40);
            $foldername = $foldername."...";
        }

        $data["name"] = $foldername;
        $data["folder"] = $args["folder_id"];
        $menu['dashboard']=true;

        $service = $this->container->get('add_folder_service');
        $service($data);

        $data["id"] = $args["folder_id"];
        $id = $data["id"];

        return $response->withStatus(302)->withHeader('Location', '/dashboard/'.$id);

    }

    public function renameAction(Request $request, Response $response, array $args)
    {
        $foldername = $_POST["nombre"];

        if(strlen($foldername)>40){
            $foldername = substr($foldername, 0, 40);
            $foldername = $foldername."...";
        }

        $data["name"] = $foldername;
        $data["folder"] = $args["folder_id"];
        $menu['dashboard']=true;
        //get parent service!

        $service = $this->container->get('get_parent_service');
        $id_parent = $service($data);

        $service = $this->container->get('rename_folder_service');
        $service($data);

        $data["id"] = $id_parent;
        $id = $data["id"];//hay que pasarle el del parent!

        return $response->withStatus(302)->withHeader('Location', '/dashboard/'.$id);

    }

    public function removeAction(Request $request, Response $response, array $args)
    {
        $data["folder"] = $args["folder_id"];
        $menu['dashboard']=true;
        //get parent service!

        $service = $this->container->get('get_parent_service');
        $id_parent = $service($data);

        $service = $this->container->get('remove_folder_service');
        $service($data);

        $data["id"] = $id_parent;
        $id = $data["id"];//hay que pasarle el del parent!

        return $response->withStatus(302)->withHeader('Location', '/dashboard/'.$id);

    }
}

