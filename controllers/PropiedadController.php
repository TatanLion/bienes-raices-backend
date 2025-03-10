<?php 

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use Model\Vendedores;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController {

    // Ruta /admin
    public static function index(Router $router){ // Forma de mantener la referencia e instancia del router y no se pierda
        // debugear($router);

        $propiedades = Propiedad::all(); // Consultamos todas las propiedades
        $vendedores = Vendedores::all();
        // Obtener estado de una accion
        $resultado =  $_GET['resultado'] ?? null;

        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'vendedores' => $vendedores,
            'resultado' => $resultado
        ]);
    }

    // Ruta /propiedades/crear
    public static function crear(Router $router){

        $propiedad = new Propiedad();
        $vendedores = Vendedores::all();
        $errores = Propiedad::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // debugear($_POST);

            // Al enviar el formulario es que creamos la instancia a la clase
            $propiedad = new Propiedad($_POST['propiedad']); // La clase recibe un array y $_POST es un arreglo

            /** SUBIDA DE ARCHIVOS **/
            // Generar un nombre a la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            // SETEAR LA IMAGEN
            if($_FILES['propiedad']['tmp_name']['imagen']){
                // Realiza un resize a la imagen
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                // Guardamos el nombre de la imagen en la propiedad imagen
                $propiedad->setImagen($nombreImagen);
            }

            $errores = $propiedad->validar();

            if (empty($errores)) {
                // Validar si la carpeta existe
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                // Guardar la imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);

                // Guardar en la BD
                $propiedad->guardar();
            }

        }

        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    // Ruta /propiedades/actualizar
    public static function actualizar(Router $router){
        $id =  validarORedireccionar('/admin');
        $propiedad = Propiedad::find($id);
        $errores = Propiedad::getErrores();
        $vendedores = Vendedores::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Para no asignar uno a uno el valor, agregamos en el name del formulario propiedad, para que lo agrupe y este lo haga directamente
            $args = $_POST['propiedad'];
        
            // Metodo para sincronizar el objeto en memoria con lo enviado desde el POST
            $propiedad->sincronizar($args);
        
            // Validamos si los campos estan llenos
            $errores = $propiedad->validar();
        
            /** SUBIDA DE ARCHIVOS **/
            // Generar un nombre a la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            // SETEAR LA IMAGEN
            if($_FILES['propiedad']['tmp_name']['imagen']){
                // Realiza un resize a la imagen
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                // Guardamos en la BD el nombre de la imagen
                $propiedad->setImagen($nombreImagen);
            }
        
            // El array de errores esta vacio
            if (empty($errores)) {
                // Guardamos la imagen si se actualizo
                if($_FILES['propiedad']['tmp_name']['imagen']){
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $propiedad->guardar();
            }
        }

        $router->render('propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
        
    }

    public static function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id_eliminar'];
            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
            if($id){
                // Obtener el tipo del input hidden y validarlo para que solo se elimine lo permitido
                $tipo = $_POST['tipo'];
                if(validarTipoContenido($tipo)){
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar($id);
                }  
            }
    
        }
    }

}