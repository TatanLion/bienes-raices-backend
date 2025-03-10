<?php

define('FUNCIONES_URL', __DIR__ . "/funciones.php");
define('TEMPLATES_URL', __DIR__ . "/templates");
define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . "/imagenes/");

function incluirTemplate(string $nombre, bool $inicio = false)
{
    include TEMPLATES_URL . "/${nombre}.php";
}

function estaAutenticado() {
    session_start();
    if(!$_SESSION['login']) {
        header('Location: /');
    }
}

function debugear($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapar / Sanitizar el HTML
function s($html){
    $s = htmlspecialchars($html);
    return $s;
}

// Validar si el tipo a eliminar es valido
function validarTipoContenido($tipo){
    $tipos = ['propiedad', 'vendedor'];
    return in_array($tipo, $tipos);
}

// Muestrta los mensajes
function mostrarNotificacion($codigo){
    $mensaje = '';
    switch ($codigo) {
        case 1:
            $mensaje = "Creado Correctamente";
            break;
        case 2:
            $mensaje = "Actualizado Correctamente";
            break;
        case 3:
            $mensaje = "Eliminado Correctamente";
            break;
        default:
            $mensaje = false;
            break;
    }
    return $mensaje;
}


// Validar parametros enviados por la URL
function validarORedireccionar(string $url){
    $id =  $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if(!$id) {
        header("Location: $url");
    }
    return $id;
}