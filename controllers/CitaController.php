<?php

namespace Controllers;

use MVC\Router;

class CitaController {


    public static function index (Router $router) {
        
        isSession();
        $router->render('cita/index', [
            'nombre' =>$_SESSION['nombre'],
            'apellido' =>$_SESSION['apellido'],
            'id' =>$_SESSION['id']
        ]);
    }
}