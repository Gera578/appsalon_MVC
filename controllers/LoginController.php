<?php

namespace Controllers;

use clases\email;
use Model\usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router){
        $alertas = [];

        $auth = new usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {           
            $auth = new usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //Comprobar que exista el usuario
                $usuario = usuario::where('email', $auth->email);

                if($usuario){
                    //Verificar el password
                    if ($usuario->comprobarPasswordAndVerificado($auth->password) ){
                        // Autenticar al usuario
                        if(!isset($_SESSION)) {
                            session_start();
                        }

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }

                    }
                } else{
                    usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);      
    }

    public static function logout(){
        
        if(!isset($_SESSION)) {
              
            session_start();
        }

        $_SESSION = [];

        header('location: /');
    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === '1'){
                    //Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //enviar email
                    $email = new email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Alerta de exito
                    usuario::setAlerta('exito', 'Revisa tu email :D');
                } else{
                    usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
            
        }
        $alertas = usuario::getAlertas();

        $router->render('auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){

        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        //Buscar usuario por su token
        $usuario = usuario::where('token', $token);

        if(empty($usuario)){
            usuario::setAlerta('error', 'Token no valido');
            $error = true;
            
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo
            $password = new usuario($_POST);
            $alertas = $password->validarPassword();
            
            if(empty($alertas)){
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado){
                    header('location: /');
                }
            }
        }
        
        $alertas = usuario::getAlertas();
        $router->render('auth/recuperar', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        $usuario = new usuario($_POST);

        //Alertas vacias
        $alertas =[];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alertas este vacio

            if(empty($alertas)){
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = usuario::getAlertas();
                } else{
                    // Hashear el password
                    $usuario->hashPassword();

                    // Generar un token unico
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //Crear el usuario
                    $resultado = $usuario->guardar(); 
                    if($resultado){
                        header('location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear_cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = usuario::where('token', $token);
        if(empty($usuario)){
            // Mostrar mensaje de error
            usuario::setAlerta('error', 'Token no valido');
        } else{
            // Mostrar mensaje de confirmacion
            $usuario->confirmado = '1';
            $usuario->token = "null";
            $usuario->guardar();
            usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        $alertas = usuario::getAlertas();

        $router->render('auth/confirmar_cuenta', [
            'alertas' => $alertas
        ]);
    }
}