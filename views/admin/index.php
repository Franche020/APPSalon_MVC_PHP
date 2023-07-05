<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ .'/../templates/barra.php' ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="<?php echo $fecha ?>">
        </div>
    </form>
</div>

<div id="citas-admin">
    <ul class="citas">
    <?php 
    $citaId = '';

    if (empty($citas)) {
        ?>
        <p class="vacio">No hay citas para la fecha seleccionada</p>
    <?php
    }
    
    foreach($citas as $key=>$cita){
        ?>
        
        
        <?php if ($cita->id !== $citaId){
            $citaId = $cita->id;
            $totalAPagar = 0;
            ?>
            <li>
                <p>Cita: <span><?php echo $cita->id; ?></span></p>
                <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
                <p>Email: <span><?php echo $cita->email; ?></span></p>
                <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>
                <h3>Servicios</h3>
            
        <?php 
        }   // FIN IF  
            $totalAPagar += $cita->precio;
            ?>
            <p class="servicio"><?php echo $cita->servicio ." " .$cita->precio;?></p>
            
    <?php 
        $actual = $cita->id;
        $proximo = $citas[$key +1]->id ?? 0;

        if (esUltimo($actual, $proximo)){
            ?>
            
            <p class="total">Total a Pagar:&#9;<span class="bold"><?php echo $totalAPagar; ?> $</span></p>

            <form action="/api/eliminar" method="POST">
                <input type="number" name="id" id="id" value="<?php echo $cita->id ?>" hidden>
                <input type="submit" value="Eliminar Cita" class="boton-eliminar">
            </form>
        
    <?php }
        
    }  // Fin foreach
    ?>

    </ul>
</div>

<?php $script  = '<script src="build/js/buscador.js"></script>' ?>