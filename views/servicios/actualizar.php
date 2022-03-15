<h1 class="nombre-pagina">Actualizar</h1>

<p class="descripcion-pagina">Actualizar los servicios del formulario</p>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<?php if(isset($_SESSION['admin'])) { ?>
    <div class="barra-servicios">
        <a class="boton" href="/admin">Ver Citas</a>
        <a class="boton" href="/servicios">Ver Servicios</a>
        <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
    </div>
<?php } ?>
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form method="POST" class="formulario">

    <?php include_once __DIR__ . "/formulario.php"; ?>


    <input type="submit" value="Actualizar servicio" class="boton">
</form>