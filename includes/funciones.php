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

function isSession() :void{
    startSession();
    if (empty($_SESSION)){
        header('location: /');
    }
}

function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}