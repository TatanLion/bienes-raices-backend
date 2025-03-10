<?php

namespace Model;

// Clase principal desde donde se van a llamar los demás metodos
class ActiveRecord {
    //Base de Datos
    protected static $db; // Static porque los datos de la DB no cambian
    protected static $columnasDB = [];
    protected static $tabla = '';

    //Errores
    protected static $errores = [];

    // Definir la conexión a la BD, debe ser static por como se crea la variable
    public static function setDB($database){
        self::$db = $database;
    }

    public function guardar(){
        if(!is_null($this->id)){
            // Actualizar un registro
            // debugear('Actualizando');
            $this->actualizar();
        }else{
            // Crear un nuevo registro
            // debugear('Creando');
            $this->crear();
        }
    }

    public function crear(){

        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        // debugear($atributos);

        // echo "Guardando en la base de datos";
        $query = "INSERT INTO " . static::$tabla . " (" ;
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES ('";
        $query .= join("', '", array_values($atributos));
        $query .= "') ";
        // debugear($query);
        
        $resultado = self::$db->query($query);

        if ($resultado) {
            header('location: /admin?resultado=1');
        }
    }

    public function actualizar(){
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        // debugear($atributos);

        $valores = [];
        foreach($atributos as $key => $value){
            $valores[] = "{$key}='{$value}'";
        }
        // debugear(join(', ', $valores));

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1;";

        // debugear($query);

        $resultado = self::$db->query($query);

        if ($resultado) {
            header('location: /admin?resultado=2');
        }
    }

    // Eliminar un registro
    public function eliminar(){
        $query = "DELETE FROM " . static::$tabla . " WHERE id = '" . self::$db->escape_string($this->id) . "'; " ;
        // debugear($query);
        $resultado = self::$db->query($query);
        if ($resultado) {
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }

    public function atributos(){
        $atributos = [];
        foreach(static::$columnasDB as $columna){
            if($columna == 'id') continue; // Aqui el id no existe y ademas no lo da la BD
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar los datos
    public function sanitizarAtributos(){
        $atributos = $this->atributos();
        // debugear($atributos);
        $sanitizado = [];

        foreach($atributos as $key => $value){
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        // debugear($sanitizado);
        return $sanitizado;
    }

    // Subida de imagenes
    function setImagen($imagen){
        // Elimina la imagen previa
        if($this->id != null){
            $this->borrarImagen();
        }

        // Asignar el atributo de imagen el nombre de la imagen
        if($imagen){
            $this->imagen = $imagen;
        }
    }

    // Eliminar imagen
    public function borrarImagen(){
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if($existeArchivo){
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    // Validación
    public static function getErrores(){
        return static::$errores;
    }

    // Validación
    public function validar(){
        
        static::$errores = []; // Hacemos que cada vez se reinicie desde 0
        return static::$errores; // Static para que tome el valor que viene heredado
    }

    // Listar todas los registros -- Static ya que no debemos instanciar para mostrar los registros
    public static function all(){
        // Aqui vamos a usar static en vez de self ya que self se refiere a esta misma clase y static a lo que viene de donde se hereda 
        $query = "SELECT * FROM " . static::$tabla . "; ";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Obtiene un determinado número de registros
    public static function get($limit){
        // Aqui vamos a usar static en vez de self ya que self se refiere a esta misma clase y static a lo que viene de donde se hereda 
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $limit;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Buscar un registro por ID
    public static function find($id){
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = $id";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado); // Devuelve la primera posicion del arreglo
    }

    public static function consultarSQL($query){
        // Consultar la BD
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = []; // Esto sera un arreglo de objetos
        while($registro = $resultado->fetch_assoc()){
            $array[] = static::crearObjeto($registro);
        }

        // Liberar la memoria
        $resultado->free();

        // Retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro){
        // Crea una nueva instancia de propiedad ya que se refiere a la clase
        $objeto = new static;
        // Lo recorremos y creamos un objeto con cada uno
        foreach($registro as $key => $value){
            if(property_exists($objeto, $key)){
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = []){
        foreach($args as $key => $value){
            if(property_exists($this, $key) && !is_null($value)){
                $this->$key = $value;
            }
        }
    }
}