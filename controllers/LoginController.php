<?php

namespace Controllers;

use Classes\Email;
use Models\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router) {
        
        $router->render('auth/login');
    }
    public static function logout(){
        echo "Desde Logout";
    }
    public static function olvide(Router $router){
        $router->render('auth/olvide-password', [

        ]);
        //TODO IF request method
    }
    public static function recuperar(){
        echo "Desde recuperar";
    }
    public static function crear(Router $router){
        $usuario = new Usuario;
        // Alertas vacías
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                // verificar si el usuario esta registrado
               $resultado = $usuario->existeUsuario();

               if($resultado->num_rows){
                $alertas = Usuario::getAlertas();
               } else {
                // Hash password
                $usuario->hashPassword();

                // Generar un token unico
                $usuario->crearToken();
                // Enviar el email
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                $email->enviarConfirmacion();
                // Crear el usuario
                $resultado = $usuario->guardar();
                if ($resultado) {
                    header ('location: /mensaje');
                }
               }
            }
        }


        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function mensaje (Router $router) {

        $router->render('auth/mensaje');
    }
    public static function confirmar(Router $router) { 
        $alertas = [];
        $mensajeError = 'Lo siento, pero el token de confirmación de cuenta que has proporcionado no es válido. Por favor, asegúrate de introducir el token correcto para poder confirmar tu cuenta.<br><br> Si sigues teniendo problemas, no dudes en ponerte en contacto con nuestro equipo de soporte para obtener asistencia adicional.<br><br>Gracias.';
        
        $token = s($_GET['token']);

        
        if (strlen($token)===13){           
            $usuario = Usuario::where('token',$token) ?? new Usuario();
            
            if ($token === $usuario->token && $usuario->confirmado === '0') {
                $usuario->confirmado = 1;
                $usuario->token = '';
                $usuario->guardar();
                Usuario::setAlerta('exito','El usuario se ha confirmado correctamente, presione <a href="/">aquí</a> para acceder a la página principal');
            } else {
                Usuario::setAlerta('error', $mensajeError);
            }
            
        } else {
            Usuario::setAlerta('error', $mensajeError);
        }
        

        //TODO Revisar los mensajes para mostrar un enlace al inicio si es correcta o mostrar el correo de administracion en caso de error



        $alertas = Usuario::getAlertas();
           
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}