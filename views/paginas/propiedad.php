
<main class="contenedor seccion contenido-centrado">
    <h1 class="fw-300 centrar-texto"><?php echo $propiedad->titulo; ?></h1>
    <img src="/imagenes/<?php echo $propiedad->imagen; ?>" alt="Imagen Anuncio">
    <div class="resumen-propiedad">
        <p class="precio">$<?php echo $propiedad->precio; ?></p>
        <ul class="iconos-caracteristicas">
            <li>
                <img src="/build/img/icono_wc.svg" alt="icono wc">
                <p><?php echo $propiedad->wc; ?></p>
            </li>
            <li>
                <img src="/build/img/icono_estacionamiento.svg" alt="icono autos">
                <p><?php echo $propiedad->estacionamiento; ?></p>
            </li>
            <li>
                <img src="/build/img/icono_dormitorio.svg" alt="icono habitaciones">
                <p><?php echo $propiedad->habitaciones; ?></p>
            </li>
        </ul>
    </div>
    <!--.resumen-propiedad-->

    <p><?php echo $propiedad->descripcion; ?></p>
</main>