<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// TODO REVISAR LA PROTECCION DE LA RUTA


function esUltimo(string $actual, string $proximo) : bool{
    if ($actual !== $proximo){
        return true;
    }
    return false;
}


// Funcion que revisa si issession está vacia para dirigir a la raiz
function isSession() :void{
    startSession();
    if (empty($_SESSION)){
        header('location: /');
    }

}

// Funcion que revisa si hay sesion php, va con la parte anulada de lo anterior
function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isAdmin () :void {
    if (!isset($_SESSION['admin'])) {
        header('location: /cita');
    }
}