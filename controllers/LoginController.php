<?php

namespace Controllers;

use Classes\Email;
use Models\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router) {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            
            if (empty($alertas)) {
                $usuario = Usuario::where('email',$auth->email);
                
                if ($usuario) {
                    //Verificar el password
                    if ($usuario->comprobarPasswordYVerificado($auth->password)) {
                        // autenticar al usuario
                        isSession();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['apellido'] = $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //redireccionamiento
                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('location: /admin');
                        } else {
                            header('location: /cita');
                        }
                    }
                    
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        
        $router->render('auth/login', [
            'alertas'=> $alertas
        ]);
    }
    public static function logout(){
        echo "Desde Logout";
    }
    public static function olvide(Router $router){
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD']=== 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                if ($usuario && $usuario->confirmado = '1') {
                    // Usuario existe y confirmado

                    // Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();
                    // Enviar el email
                    $mail = new Email($usuario->email, $usuario->nombre. ' ' .$usuario->apellido, $usuario->token);
                    $mail->enviarRecuperacion();

                    Usuario::setAlerta('exito', 'El email de recuperacion ha sido enviado, por favor comprueba tu email');
                } else {
                    // Usuario no existe o no está confirmado.
                    Usuario::setAlerta('error', 'El usuario no existe o no está verificado');
                }

            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $exito = false;
        // Token desde get sanitizado
        if (!isset($_GET['token'])){
            $token = '';
        } else {
            $token = s($_GET['token'])??'';
        }
        // Buscar usuario por token
        $usuario = Usuario::where('token', $token);

        // Comprobar que el usuario existe y que el token es valido
        if ($usuario && strlen($token) === 13) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                $password = new Usuario($_POST);
                // Validar Password
                $alertas = $password->validarPassword();
                if (empty($alertas)) {
                    
                    $password->hashPassword();
                    $usuario->password = null;
                    $usuario->password = $password->password;
                    $usuario->token = '';
                    $usuario->guardar();
    
                    Usuario::setAlerta('exito', 'La contraseña se ha cambiado correctamente');
                    $exito = true;
                }

    
            }
            
        } else {            
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error,
            'exito' => $exito
        ]);
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



        $alertas = Usuario::getAlertas();
           
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas,
        ]);
    }
}