<?php 

namespace Controllers;

use Model\Propiedad;
use MVC\Router;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {

    public static function index(Router $router){

        $inicio = true;
        $propiedades = Propiedad::get(3);

        $router->render('paginas/index', [
            'inicio' => $inicio,
            'propiedades' => $propiedades
        ]);

    }

    public static function nosotros(Router $router){

        $router->render('paginas/nosotros', []);

    }

    public static function propiedades(Router $router){

        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);

    }

    public static function propiedad(Router $router){

        $id =  validarORedireccionar('/propiedades');
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);

    }

    public static function blog(Router $router){

        $router->render('paginas/blog', []);

    }

    public static function entrada(Router $router){

        $router->render('paginas/entrada', []);

    }

    public static function contacto(Router $router){

        $mensaje = null;
        $isError = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            // Capturar la información que se lleno en le formulario
            $respuestas = $_POST['contacto'];

            // Crear una instancia de PHPMailer
            $mail = new PHPMailer();

            // Configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'a17a62c460f279';
            $mail->Password = 'a5201771079954';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            // Configurar el contenido del Mail
            $mail->setFrom('admin@bienesraices.com');
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com');
            $mail->Subject = 'Tienes un nuevo mensaje';

            // Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            
            // Definir el contenido
            $contenido = '<html>';
            $contenido .= '<p>Tienes un nuevo mensaje </p>';
            $contenido .= '<p>Nombre: ' . $respuestas['nombre'] . '</p>';
            // Enviar de forma condicional algunos campos de email o teléfono
            if($respuestas['contacto'] === 'telefono'){
                $contenido .= '<p>Eligio ser contactado por teléfono</p>';
                $contenido .= '<p>Teléfono: ' . $respuestas['telefono'] . '</p>';
                $contenido .= '<p>Fecha Contacto: ' . $respuestas['fecha'] . '</p>';
                $contenido .= '<p>Hora Contacto: ' . $respuestas['hora'] . '</p>';
            }else{
                // Es email, entonces vamos a agregar el campo de email
                $contenido .= '<p>Eligio ser contactado por email</p>'; 
                $contenido .= '<p>Email: ' . $respuestas['email'] . '</p>';
            }
            $contenido .= '<p>Mensaje: ' . $respuestas['mensaje'] . '</p>';
            $contenido .= '<p>Vende o Compra: ' . $respuestas['opciones'] . '</p>';
            $contenido .= '<p>Precio o presupuesto: ' . $respuestas['presupuesto'] . '</p>';
            $contenido .= '</html>';

            // Agregar contenido
            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            // Envial el email
            if($mail->send()){
                $mensaje = "Correo enviado correctamente";
                $isError = false;
            }else{
                $mensaje = "El correo no se pudo enviar";
                $isError = true;   
            }

        }

        $router->render('paginas/contacto', [
            'mensaje' => $mensaje,
            'isError' => $isError
        ]);

    }

}