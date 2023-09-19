<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Rellena los campos para actualizar el servicio</p>

<?php //include __DIR__. '/../templates/barra.php' ?>
<?php include __DIR__. '/../templates/alertas.php' ?>

<form method="POST" class="formulario">

    <?php @include_once __DIR__. '/formulario.php' ?>

    <input type="submit" value="Actualizar" class="boton">
</form>
