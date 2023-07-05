<?php

namespace Controllers;

use Models\Cita;
use Models\CitaServicio;
use Models\Servicio;

class APIController{

    public static function index () {
        isSession();
        
        $servicios = Servicio::all();

        echo json_encode($servicios);
    }

    public static function guardar() {
        // Almacena la cita y devuelve el ID
        isSession();

        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'];

        // Almmacena la cita y los servicios
        $idServicios = explode(",", $_POST['servicios']);

        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        // retornamos una respuesta
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar () {
        isSession();
        isAdmin();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = s($_POST['id']);
            $referer = $_SERVER["HTTP_REFERER"];
            $cita = Cita::find($id);
            $cita->eliminar();

            header('location:' .$referer);
        }
        
    }
}