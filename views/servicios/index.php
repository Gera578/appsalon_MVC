<h1 class="nombre-pagina">Servicios</h1>

<p class="descripcion-pagina">Administracion de servicios</p>

<?php include_once __DIR__ . '/../templates/barra.php';?>

<?php if(isset($_SESSION['admin'])) { ?>
    <div class="barra-servicios">
        <a class="boton" href="/admin">Ver Citas</a>
        <a class="boton" href="/servicios">Ver Servicios</a>
        <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
    </div>
<?php } ?>

<ul class="servicios">

    <?php foreach($servicios as $servicio) {?>

        <li>
            <p>Nombre: <span><?php echo $servicio->nombre; ?></span></p>
            <p>Precio: $<span><?php echo $servicio->precio; ?></span></p>

            <div class="acciones">
                <a class="boton"href="/servicios/actualizar?id=<?php echo $servicio->id;?>">Actualizar</a>

                <form action="/servicios/eliminar" method="POST">
                    <input type="hidden" value="<?php echo $servicio->id;?>" name="id">

                    <input type="submit" value="borrar" class="boton-eliminar">
                </form>
            </div>

        </li>

    <?php } ?>
</ul>