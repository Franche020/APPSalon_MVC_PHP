<?php

namespace Controllers;

use Models\Servicio;
use MVC\Router;

class ServiciosController {

    public static function index(Router $router) {

        isSession();
        isAdmin();


        $servicios = Servicio::all();
        
        $router->render('/servicios/index', [

            'nombre' => $_SESSION['nombre'],
            'apellido' => $_SESSION['apellido'],
            'servicios' => $servicios
        ]);
    }

    public static function crear (Router $router) {

        isSession();
        isAdmin();



        $servicio = new Servicio();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();

                header('location: /servicios');
            }
        }

        $router->render('/servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'apellido' => $_SESSION['apellido'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar (Router $router){

        isSession();
        isAdmin();


        
        if(!is_numeric($_GET['id'])) {
            header('location: /servicios');
            return;
        } else {
            $id = $_GET['id'];
        }
        
        $servicio = Servicio::find($id);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $servicio->sincronizar($_POST);
            $alertas= $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
 
                header('location: /servicios');
            }
            
        }

        $router->render('/servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'apellido' => $_SESSION['apellido'],
            'alertas' => $alertas,
            'servicio' => $servicio
        ]);
    }

    public static function eliminar (){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            isSession();
            isAdmin();

            $id = $_POST['id'];

            $servicio = Servicio::find($id);

            $servicio->eliminar();

            header('location: /servicios');
            
        }
    }
}