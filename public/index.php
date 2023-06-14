<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\APIController;
use MVC\Router;
use Controllers\CitaController;
use Controllers\LoginController;

$router = new Router();

// Iniciar sesión
$router->get('/',[LoginController::class, "login"]);
$router->post('/',[LoginController::class, "login"]);
// Cerrar sesión
$router->get('/logout',[LoginController::class, "logout"]);

// Recuperar password
$router->get('/olvide',[LoginController::class, "olvide"]);
$router->post('/olvide',[LoginController::class, "olvide"]);
$router->get('/recuperar',[LoginController::class, "recuperar"]);
$router->post('/recuperar',[LoginController::class, "recuperar"]);

// Creacion cuentas
$router->get('/crear-cuenta',[LoginController::class, "crear"]);
$router->post('/crear-cuenta',[LoginController::class, "crear"]);

// Confirmacion de creacion de cuenta
$router->get('/confirmar-cuenta', [LoginController::class, "confirmar"]);
$router->get('/mensaje', [LoginController::class , "mensaje"]);

// Area Privada
$router->get('/cita', [CitaController::class, "index"]);


// API de dictas
$router->get('/api/servicios', [APIController::class,"index"]);
$router->post('/api/citas', [APIController::class,"guardar"]);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();  