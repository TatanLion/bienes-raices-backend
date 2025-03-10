<?php 

namespace MVC;

class Router{

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn){
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn){
        $this->rutasPOST[$url] = $fn;
    }

    // Verificar ruta y mandar a llamar la función
    public function comprobarRutas(){

        // Iniciar una sesión
        session_start();

        // Verificamos si esta logeado o no
        $auth = $_SESSION['login'] ?? null;

        // Arreglo de rutas protegidas
        $rutas_protegidas = [
            '/admin',
            '/propiedades/crear',
            '/propiedades/actualizar', 
            '/propiedades/eliminar', 
            '/vendedores/crear', 
            '/vendedores/actualizar', 
            '/vendedores/eliminar'
        ];
    
        // Obtener la url actual y el metodo
        $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        // Verificar el metodo y ejecutar de acuerdo a eso 
        if($metodo === 'GET'){
            $fn = $this->rutasGET[$urlActual] ?? null;
        }else{
            // debugear($this);
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        if(in_array($urlActual, $rutas_protegidas) && !$auth){
            header('Location: /');
        }

        if($fn){
            // LA URL existe y tiene una función asociada
            // debugear($fn);
            // debugear($this);
            call_user_func($fn, $this);
        }else{
            echo "Pagina no encontrada";
        }
    }

    // Mostrar una vista basado en la ruta
    public function render($view, $datos = []){

        foreach($datos as $key => $value ){
            $$key = $value; // El $$ crea una variable con ese nombre que pasemos ya que no sabemos como se llamara
        }

        ob_start(); // Almacenamiento en memoria durante un momento...
        include __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean(); // Limpia el buffer de memoria que estaba usando

        include __DIR__ . "/views/layout.php";
    }

}