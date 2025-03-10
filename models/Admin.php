<?php

namespace Model;
use Model\ActiveRecord;

class Admin extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'email', 'password'];

    public $id;
    public $email;
    public $password;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }

    public function validar(){
        if(!$this->email){
            self::$errores[] = 'El Email es obligatorio';
        }
        if(!$this->password){
            self::$errores[] = 'El password es obligatorio';
        }

        return self::$errores;
    }

    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1;";
        $resultado = self::$db->query($query);
        if(!$resultado->num_rows){
            self::$errores[] = "El usuario no existe";
            return;
        }
        return $resultado;
    }

    public function comprobarPassword($resultado){
        // Traemos resultado ya que a estas alturas tenemos la información allí
        $usuario = $resultado->fetch_object();

        // Pasamos primero el hash que ingreso el usuario y de segundas el que esta almacenado en la BD
        $autenticado = password_verify($this->password, $usuario->password);

        if(!$autenticado){
            self::$errores[] = "El password es incorrecto";
        }

        return $autenticado;
    }

    public function autenticar(){
        session_start();

        // LLenar arreglo del usuario
        $_SESSION['usuario'] = $this->email;
        $_SESSION['login'] = $this->email;

        header('Location: /admin');
    }

}