<?php

namespace Controllers;

use Models\AdminCita;
use MVC\Router;

class AdminController {

    public static function index (Router $router){

        isSession();
        isAdmin();

        $fecha = date('Y-m-d'); // Variable asignada por defecto al abrir la pagina

        if (isset($_GET['fecha'])){ // Comprobacion de que $_GET['fecha'] tiene datos
            $fechaSeleccionada = s($_GET['fecha']) ?? ''; // Asignacion sanitizada a $fechaSeleccionada, el placeholder es para evitar posibles errores pero creo que sobra

            // Comprobacion de que la fecha es valida
            $fechaCheck= explode('-', $fechaSeleccionada); 
            $fechaCheck = checkdate($fechaCheck[1],$fechaCheck[2],$fechaCheck[0]);

            if (isset($fechaSeleccionada) && $fechaCheck){
                $fecha = $fechaSeleccionada;
            } else {
                header('location: /404');
            }
        }


        // Consultar la DB
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '{$fecha}' ";

        $citas = AdminCita::SQL($consulta);

        
        $router->render('/admin/index', [
            'nombre' => $_SESSION['nombre'],
            'apellido' => $_SESSION['apellido'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}