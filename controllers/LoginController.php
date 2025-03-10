<?php

namespace Controllers;
use MVC\Router;
use Model\Admin;

class LoginController{
    public static function login(Router $router){

        $erorres = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Admin($_POST);

            $erorres = $auth->validar();

            if(empty($erorres)){

                // Verificar si el usuario existe
                $resultado = $auth->existeUsuario();

                if(!$resultado){
                    // Verificar si el usuario existe o no (mensaje de error)
                    $erorres = Admin::getErrores();
                }else{
                    // Verificar el password
                    $autenticado = $auth->comprobarPassword($resultado);

                    if($autenticado){
                        // Autenticar el usuario
                        $auth->autenticar();
                    }else{
                        // Password Incorrecto (mensaje de error)
                        $erorres = Admin::getErrores();
                    }
                }
            }
        }

        $router->render('/auth/login', [
            'errores' => $erorres
        ]);
    }

    public static function logout(){ 
        // Accedemos a la sesión actual
        session_start();

        // Borramos los datos de la sesión actual
        $_SESSION = [];

        header('Location: /login');
    }
}