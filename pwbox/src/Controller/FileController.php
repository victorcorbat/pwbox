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

class FileController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function renameAction(Request $request, Response $response, array $args)
    {

        $filename = $_POST["nombre"];

        if(strlen($filename)>40){
            $filename = substr($filename, 0, 40);
            $filename = $filename."...";
        }
        $data["name"] = $filename;
        $data["file"] = $args["file_id"];

        $service = $this->container->get('get_folder_service');
        $id_parent = $service($data);

        $service = $this->container->get('rename_file_service');
        $service($data);

        return $response->withStatus(302)->withHeader('Location', '/dashboard/'.$id_parent);

    }

    public function removeAction(Request $request, Response $response, array $args)
    {
        $data["file"] = $args["file_id"];
        //get parent service!

        $service = $this->container->get('get_folder_service');
        $id_parent = $service($data);

        $service = $this->container->get('remove_file_service');
        $service($data);

        return $response->withStatus(302)->withHeader('Location', '/dashboard/'.$id_parent);

    }
}

