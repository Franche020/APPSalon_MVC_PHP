<?php

namespace Models;

class Servicio extends ActiveRecord {
    // Base de datos configuracion

    protected static $tabla = "servicios";
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
}
