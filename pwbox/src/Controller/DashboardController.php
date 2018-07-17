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
        if(!isset($_SESSION['id'])){
            return $response->withStatus(302)->withHeader('Location', '/');
        }
        $data['id'] = $_SESSION['id'];
        $service = $this->container->get('user_service');
        $user = $service($data);
        $menu['dashboard']=true;
        $menu['profile']=false;
        $menu['shared']=false;
        $mensaje_error = false;

        if(isset($args["folder_id"]) && isset($_SESSION["id"])){
            $data["id"] = $args["folder_id"];
            $id = $data["id"];

            $data['folder']=$id;
            $service = $this->container->get('get_parent_service');
            $parent = $service($data);

            $service = $this->container->get('creator_service');
            $creator = $service($data);

            $service = $this->container->get('folder_name_service');
            $title = $service($data);

            if($creator == $_SESSION['id']){
                $data["id"] = $args["folder_id"];
                //mirar si al root puedo acceder
                $service = $this->container->get('folders_inside_service');
                $folders = $service($data);
                $service = $this->container->get('files_inside_service');
                $files = $service($data);
                return $this->container->get('view')
                    ->render($response, 'dashboard.twig', ['folders'=>$folders, 'id'=>$id, 'title'=>$title, 'parent'=>$parent, 'files'=>$files, 'menu'=>$menu, 'mensaje_error'=>$mensaje_error]);
            }
            else{
                $mensaje_error = true;
            }
            //get creator id!
            //comparar el id con el del creador
            //mirar si es igual al del session. si lo son acceder.

        }
        if(isset($_SESSION["id"])){
            $id = $_SESSION["id"];
            $data["id"]=$id;
            $title = "Carpeta root";
            //hay que obtener todos las carpetas y los archivos
            $service = $this->container->get('folders_user_service');
            $folder = $service($data);
            $id = $folder['id'];
            $parent = $id;
            $data["id"] = $id;
            $service = $this->container->get('folders_inside_service');
            $folders = $service($data);
            $service = $this->container->get('files_inside_service');
            $files = $service($data);

            return $this->container->get('view')
                ->render($response, 'dashboard.twig', ['folders'=>$folders, 'id'=>$id, 'title'=>$title, 'parent'=>$parent, 'files'=>$files, 'menu'=>$menu, 'mensaje_error'=>$mensaje_error]); //obtener todas las carpetas compar
        }

        return $this->container->get('view')
            ->render($response, 'login.twig');
        // return con el ninguna de las 2--> estan intentando acceder desde la barra
    }
}


