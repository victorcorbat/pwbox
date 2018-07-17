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

class RegisterController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response, array $args)
    {
        return $this->container->get('view')
            ->render($response, 'register.twig');
    }

    public function registerAction(Request $request, Response $response, array $args)
    {
        //$errors = '';
        $exists = false;
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('post_user_use_case');

            if(isset($data["username"]) && isset($data["email"]) && isset($data["birthdate"]) && isset($data["password"]) && isset($data["password_confirmation"])) {

                if (!$this->validUsername($data["username"])) {
                    $errors['username'] = "El usuario debe de tener 6-12 carácteres";
                }
                if (!$this->validEmail($data["email"])) {
                    $errors['email'] = "El formato del email es incorrecto";
                }
                if (!$this->validBirthdate($data["birthdate"])) {
                    $errors['birthdate'] = "El formato de fecha debe ser AAAA-MM-DD ";
                }
                else{
                    if(!$this->validBirthFields($data["birthdate"])){
                        $errors['birthdate'] = "La fecha introducida no es válida";
                    }
                    else{
                        if(!$this->afterBirthdate($data["birthdate"])){
                            $errors['birthdate'] = "La fecha debe de ser anterior a la actual";
                        }
                    }
                }
                if (!$this->validPassword($data["password"])) {
                    $errors['password'] = "La contraseña debe contener 6-12 carácteres, 1 mayuscula y 1 número";
                }
                if (!$this->equalsPasswords($data["password"], $data["password_confirmation"])) {
                    $errors['passwords'] = "Las contraseñas no coinciden";
                }
                if(empty($errors)){
                    $id = $service($data);
                    //obtener id!
                }
            }
            else{
                $error['fields'] = 'Tienes que llenar todos los campos';
            }

        }catch(\Exception $e){
            $response = $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write($e->getMessage());
        }
        if(isset($errors) || $id==-1){
            $exists = true;
            if(!isset($errors)){
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['data'=>$data, 'exists'=>$exists]);
            }
            return $this->container->get('view')
                ->render($response, 'register.twig', ['errors'=> $errors, 'data'=>$data, 'exists'=>$exists]);
        }
        //Redirigir a la página de la foto de perfil!
        return $response->withStatus(302)->withHeader('Location', '/uploadPic/'.$id); //insertar id del usuario para hacer update con la foto de perfil
        //return $response->withStatus(302)->withHeader('Location', '/');
    }

    public function validUsername(String $username){
        if(strlen($username)<=20 && strlen($username)>0){
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

    public function validBirthFields(String $birthdate){
        $ymd = explode('-', $birthdate);
        $year_b = $ymd[0];
        $month_b = $ymd[1];
        $day_b = $ymd[2];
        if($month_b>12 || $month_b<=0 || $day_b<=0 || $day_b>31){
            return false;
        }
        return true;
    }

    public function afterBirthdate(String $birthdate){
        $ymd = explode('-', $birthdate);
        $year_b = $ymd[0];
        $month_b = $ymd[1];
        $day_b = $ymd[2];
        $now = new \Datetime('now');
        $date = $now->format('Y-m-d');
        $ymd_n = explode('-', $date);
        $year_n = $ymd_n[0];
        $month_n = $ymd_n[1];
        $day_n = $ymd_n[2];
        $days_b = $year_b * 365 + $month_b * 30 + $day_b;
        $days_n = $year_n * 365 + $month_n * 30 + $day_n;
        if($days_b > $days_n){
            return false;
        }
        return true;
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

