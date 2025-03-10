<?php 

namespace Controllers;

use Model\Vendedores;
use MVC\Router;

class VendedorController {

    public static function crear(Router $router){
        
        $errores = Vendedores::getErrores();
        $vendedor = new Vendedores();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $vendedor = new Vendedores($_POST['vendedor']);
            // debugear($vendedor);
        
            // Validar que no hayan campos vacios y almacenarlo en la variable para iterar sobre ellos
            $errores = $vendedor->validar();
        
            if(empty($errores)){
                $vendedor->guardar();
            }
        }

        $router->render('vendedores/crear', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

    public static function actualizar(Router $router){

        $id = validarORedireccionar('/admin');

        $vendedor = Vendedores::find($id);
        $errores = Vendedores::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asignar los valores del formulario
            $args = $_POST['vendedor'];
    
            // Sincroniza los valores que se escribieron con el objeto en memoria
            $vendedor->sincronizar($args);
    
            // Validamos el formulario
            $errores = $vendedor->validar();
    
            // Si no hay errores
            if(empty($errores)){
                $vendedor->guardar();
            }
        }

        $router->render('vendedores/actualizar', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);

    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id_eliminar'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id){
                // Validar el tipo a eliminar
                $tipo = $_POST['tipo'];
                if(validarTipoContenido($tipo)){
                    $vendedor = Vendedores::find($id);
                    $vendedor->eliminar();
                }
            }
        }
    }

}