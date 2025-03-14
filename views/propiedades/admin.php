<main class="contenedor seccion contenido-centrado">
    
    <h1 class="fw-300 centrar-texto">Administración</h1>
    <!-- Errores -->
    <?php 
        if($resultado):
            $mensaje = mostrarNotificacion(intval($resultado));
            if($mensaje) :?>
                <p class="alerta exito"> <?php echo s($mensaje) ?> </p>
            <?php endif; endif; ?>
        
    <!-- Errores -->
    
    <a href="/propiedades/crear" class="boton boton-verde">Nueva Propiedad</a>
    <a href="/vendedores/crear" class="boton boton-amarillo">Nuevo Vendedor</a>

    <h2>Propiedades</h2>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach( $propiedades as $propiedad ): ?>
            <tr>
                <td><?php echo $propiedad->id; ?></td>
                <td><?php echo $propiedad->titulo; ?></td>
                <td>
                    <img src="/imagenes/<?php echo $propiedad->imagen; ?>"" width="100" class="imagen-tabla">
                </td>
                <td>$ <?php echo $propiedad->precio; ?></td>
                <td>
                <form method="POST" action="/propiedades/eliminar">
                    <input type="hidden" name="id_eliminar" value="<?php echo $propiedad->id; ?>">
                    <input type="hidden" name="tipo" value="propiedad">
                    <input type="submit" href="/propiedades/borrar" class="boton boton-rojo-block" value="Borrar">
                </form>
                    
                    <a href="/propiedades/actualizar?id=<?php echo $propiedad->id; ?>" class="boton boton-verde">Actualizar</a>
                </td>
            </tr>

            <?php endforeach; ?>
        </tbody>
    </table>


    <h2>Vendedores</h2>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Télefono</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach( $vendedores as $vendedor ): ?>
            <tr>
                <td><?php echo $vendedor->id; ?></td>
                <td>
                    <?php echo $vendedor->nombre . " " . $vendedor->apellido; ?>
                </td>
                <td><?php echo $vendedor->telefono; ?></td>
                <td>
                <form method="POST" action="/vendedores/eliminar">
                    <input type="hidden" name="id_eliminar" value="<?php echo $vendedor->id; ?>">
                    <input type="hidden" name="tipo" value="vendedor">
                    <input type="submit" class="boton boton-rojo-block" value="Eliminar">
                </form>
                    
                    <a href="/vendedores/actualizar?id=<?php echo $vendedor->id; ?>" class="boton boton-verde">Actualizar</a>
                </td>
            </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

</main>
