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


class ProfileController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response, array $args)
    {
        $menu['dashboard']=false;
        $menu['profile']=true;
        $menu['shared']=false;

        $data['id'] = $_SESSION['id'];
        $service = $this->container->get('user_service');
        $user = $service($data);
        return $this->container->get('view')
            ->render($response, 'update.twig', ['user' => $user, 'menu'=>$menu]);
    }

    public function removeAction(Request $request, Response $response, array $args)
    {
        $data["user_id"] = $args["user_id"];
        $service = $this->container->get('remove_user_service');
        $service($data);
        $_SESSION['id']='';
        return $this->container->get('view')
            ->render($response, 'login.twig');
    }

    public function updateAction(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        $data['id'] = $_SESSION['id'];
        $service = $this->container->get('user_service');
        $user = $service($data);
        if(isset($data["password"]) && isset($data["confirm_pass"])) {
            if(!$this->validPassword($data["password"])){
                $error['password']="La contraseña debe de tener almenos 1 mayúscula, 1 minúscula y 1 número";
            }
            else{
                if(!$this->equalsPasswords($data["password"], $data["confirm_pass"])){
                    $error['passwords']="La contraseña no coinciden";
                }
                else{
                    $user = $this->container->get('update_service')->updatePass($data);
                }
            }
        }
        else {
            if(isset($data["email"]) && isset($data["birthdate"])) {
                if(!$this->validEmail($data["email"])){
                    $error['email']="El formato del email no es valido";
                }
                else{
                    if(!$this->validBirthdate($data["birthdate"])){
                        $error['birthdate']="La fecha de nacimiento debe de ser AAAA-MM-DD";
                    }
                    else{
                        $user = $this->container->get('update_service')->updateData($data);
                    }
                }
            }

        }

        $menu['dashboard']=false;
        $menu['profile']=true;
        $menu['shared']=false;

        if(isset($error)){
            return $this->container->get('view')
                ->render($response, 'update.twig', ['user' => $user, 'error'=>$error, 'menu'=>$menu]);
        }

        $mensaje = true;

        return $this->container->get('view')
            ->render($response, 'update.twig', ['user' => $user, 'menu'=>$menu, 'mensaje'=>$mensaje]);
    }

    public function validUsername(String $username){
        if(strlen($username)<=20){
            return true;
        }
        return false;
    }

    public function validEmail(String $email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    // 2012-02-12
    public function validBirthdate(String $birthdate){
        if (preg_match("/\d{4}\-\d{2}-\d{2}/", $birthdate)) {
            return true;
        }
        return false;
    }

    public function validPassword(String $password){
        if(strlen($password)<6 || strlen($password)>12){
            return false;
        }

        if(!preg_match("#[0-9]+#",$password)){
            return false;
        }
        if(!preg_match("#[A-Z]+#",$password)){
            return false;
        }
        if(!preg_match("#[a-z]+#",$password)){
            return false;
        }
        return true;
    }

    public function equalsPasswords(String $password, String $confirm){
        if($password==$confirm){
            return true;
        }
        return false;
    }

}


