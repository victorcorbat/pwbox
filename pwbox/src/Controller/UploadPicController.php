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
use Slim\Http\UploadedFile;

class UploadPicController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response, array $args)
    {
        $user_id = $args['user_id'];

        if($_SESSION['id_pic']==$user_id || $_SESSION['id']==$user_id){
            return $this->container->get('view')
                ->render($response, 'profilePic.twig', ['user_id'=>$user_id]); //pasarle el id que está en args!
        }
        else{
            return $response->withStatus(302)->withHeader('Location', '/'); //enviarle un mensaje diciendo que no puede acceder a esta página
        }
    }


    public function uploadAction(Request $request, Response $response, array $args)
    {
        //RECIBE UN POST CON LA INFO??!
         //devuelve el id del archivo!
        $id_pic = $_SESSION['id_pic'];
        $user_id = $args['user_id'];
        $menu['dashboard']=true;
        $directory = $_SERVER['DOCUMENT_ROOT'];
        $id_user = $args['user_id'];

        $uploadedFiles = $request->getUploadedFiles();

        // handle single input with single file upload
        $uploadedFile = $uploadedFiles['file'];
        $error = '';
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $res = $this->moveUploadedFile($directory, $uploadedFile);
        }
        if(!isset($res) && isset($_SESSION['id_pic']) && $_SESSION['id_pic']!=''){
            return $response->withStatus(302)->withHeader('Location', '/uploadPic/'.$_SESSION['id_pic']);
        }

        if(!isset($res) && isset($_SESSION['id'])){
            return $response->withStatus(302)->withHeader('Location', '/profile');
        }

        if($res==1 || $res==2){

            if($res==1){
                $error = "El formato del archivo debe de ser JPG o PNG";
            }

            if($res==2){
                $error = "El tamaño del archivo es superior a 500Kb";
            }

            //El error lo tengo, solo hay que pasarselo!!

            $user_id = $args['user_id'];

            if($_SESSION['id_pic']==$user_id){
                return $this->container->get('view')
                    ->render($response, 'profilePic.twig', ['user_id'=>$user_id, 'error'=>$error]); //pasarle el id que está en args!
            }
            if($_SESSION['id']==$user_id){

                //conseguir todos los datos!

                if(!isset($_SESSION['id'])){
                    return $response->withStatus(302)->withHeader('Location', '/');
                }


                $menu['dashboard']=false;
                $menu['profile']=true;
                $menu['shared']=false;

                $data['id'] = $_SESSION['id'];
                $service = $this->container->get('user_service');
                $user = $service($data);

                return $this->container->get('view')
                    ->render($response, 'update.twig', ['user' => $user, 'menu'=>$menu, 'error'=>$error]);
            }

            return $response->withStatus(302)->withHeader('Location', '/'); //enviarle un mensaje diciendo que no puede acceder a esta página


            //return $response->withStatus(302)->withHeader('Location', '/uploadPic/'.$user_id); //pasarle el id de la imagen
        }
        else{
            $filename = $res;
            $data['filename'] = $filename;
            $data['user_id'] = $id_user;
            //UPDATE
            $service = $this->container->get('update_pic_service');
            $service($data);
            $_SESSION['id_pic'] = '';
            if($id_pic!=''){
                return $response->withStatus(302)->withHeader('Location', '/'); //pasarle el id de la imagen
            }
            else{
                return $response->withStatus(302)->withHeader('Location', '/profile'); //pasarle el id de la imagen
            }

        }
    }

    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param string $directory directory to which the file is moved
     * @param UploadedFile $uploaded file uploaded file to move
     * @return string filename of moved file
     */
    public function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $error='';

        if($extension!='jpg' && $extension!='png' && $extension!='PNG'  && $extension!='JPG'){
            $error = "El formato del archivo no es soportado";
            return 1;
        }
        else{
            if($uploadedFile->getSize()>500e3){
                $error = "El tamaño del archivo es superior a 500Kb";
                return 2;
            }
            else{
                $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
                return $filename;
            }
        }
    }

}

