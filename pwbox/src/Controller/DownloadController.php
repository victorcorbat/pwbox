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


class DownloadController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function downloadAction(Request $request, Response $response, array $args)
    {
        $filename = $args['id'];
        $separated = explode('.', $filename);
        $data['id']=$separated[0];
        $service = $this->container->get('file_creator_service');
        $creator = $service($data);
        //mirar si esa carpeta está compartida conmigo
        //sacamos el identificador de la carpeta donde está el archivo.
        //sacamos la cadena
        //miramos si esa carpeta está compartida con nosotros.
        $data['file']=$separated[0];
        $service = $this->container->get('get_folder_service');
        $folder = $service($data);

        $data['id']=$folder;
        $service = $this->container->get('chain_service');
        $aux = $service($data)."-".$folder;
        $chain = explode("-", $aux);
        $access = false;

        for($i=1; $i<sizeof($chain) && $access == false; $i++){
            if($this->accesible($_SESSION['id'], $chain[$i])){
                $access = true;
            }
        }

        if($_SESSION['id']==$creator || $access == true){
            $filePath = __DIR__ .'/uploads/'.$filename;

            if(file_exists($filePath)) {
                $fileName = basename($filePath);
                $fileSize = filesize($filePath);

                // Output headers.
                header("Cache-Control: private");
                header("Content-Type: application/stream");
                header("Content-Length: ".$fileSize);
                header("Content-Disposition: attachment; filename=".$fileName);

                // Output file.
                readfile ($filePath);
                exit();
            }
            else {
                die('The provided file path is not valid.');
            }
        }

        // Fetch the file info.

    }

    public function accesible($user_id, $folder_id ){
        $data['user_id']=$user_id;
        $data['folder_id']=$folder_id;
        $service = $this->container->get('accessible_service');
        return $service($data);
    }
}

