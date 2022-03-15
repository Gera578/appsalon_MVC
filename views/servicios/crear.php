<h1 class="nombre-pagina">Crear</h1>

<p class="descripcion-pagina">Llena todos los campos para poder añadir un nuevo servicio</p>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<?php if(isset($_SESSION['admin'])) { ?>
    <div class="barra-servicios">
        <a class="boton" href="/admin">Ver Citas</a>
        <a class="boton" href="/servicios">Ver Servicios</a>
        <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
    </div>
<?php } ?>
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="/servicios/crear" method="POST" class="formulario">

    <?php include_once __DIR__ . "/formulario.php"; ?>


    <input type="submit" value="Guardar servicio" class="boton">
</form>