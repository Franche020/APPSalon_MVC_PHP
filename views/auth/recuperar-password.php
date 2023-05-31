<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Introduce tu nuevo password a continuacion</p>

<?php 
include_once __DIR__ . '/../templates/alertas.php'; 
?>
<?php 
if ($error === false && $exito === false): ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="contraseña">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="nuevo Password">
    </div>
    <input type="submit" value="Recuperar cuenta" class="boton">
</form>
<?php endif; ?>
<div class="acciones">
    <?php if($exito === false) : ?>
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <?php else : ?>
        <a href="/">Inicia Sesión en el siguiente enlace</a>
    <?php endif; ?>
</div>