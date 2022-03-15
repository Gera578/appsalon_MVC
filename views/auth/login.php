<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesion con tus datos</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input 
        type="email"
        id="email"
        placeholder="Tu email"
        name="email"
        value="<?php echo s($auth->email); ?>"
        />
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input 
        type="password"
        id="password"
        placeholder="Tu password"
        name="password"
        />
    </div>

    <input type="submit" value="Iniciar sesion" class="boton">
</form>

<div class="acciones">
    <a href="/crear_cuenta">¿Aun no tienes una cuenta? Crea una aqui</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>