<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">LLena todos los campos para añadir un nuevo servicio</p>

<?php //include __DIR__. '/../templates/barra.php' ?>
<?php include __DIR__. '/../templates/alertas.php' ?>

<form action="/servicios/crear" method="POST" class="formulario">

    <?php @include_once __DIR__. '/formulario.php' ?>

    <input type="submit" value="Crear Servicio" class="boton">
</form>