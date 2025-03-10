<?php 

namespace Model;

class Vendedores extends ActiveRecord{

    protected static $tabla = 'vendedores';
    protected static $columnasDB =  ['id', 'nombre', 'apellido', 'telefono'];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;

    public function __construct($args = [])
    {
        $this -> id = $args['id'] ?? null;
        $this -> nombre = $args['nombre'] ?? '';
        $this -> apellido = $args['apellido'] ?? '';
        $this -> telefono = $args['telefono'] ?? '';
    }

    // Validación
    public function validar(){
        if (!$this->nombre) {
            self::$errores[] = 'Debes añadir un Nombre';
        }
        if (!$this->apellido) {
            self::$errores[] = 'Debes añadir un Apellido';
        }
        if (!$this->telefono) {
            self::$errores[] = 'El teléfono es obligatorio';
        }
        if(!preg_match('/[0-9]{10}/', $this->telefono)){
            self::$errores[] = 'Formato no válido';
        }

        return self::$errores;
    }

}

