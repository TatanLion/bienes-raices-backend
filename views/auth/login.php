<main class="contenedor seccion contenido-centrado">
    <h1 class="fw-300 centrar-texto">Iniciar Sesión</h1>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form method="POST" class="formulario" novalidate>
        <fieldset>
            <legend>Email y Password</legend>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Tu Email" >

            <label for="password">Password: </label>
            <input type="password" name="password" id="password" placeholder="Tu Password" >
        </fieldset>
        <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
    </form>
</main>