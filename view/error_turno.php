<?php
// Si obtiene el valor "error" en $dataToView 
$error = $dataToView["data"]["error"] ?? "";
?>


<div class="container mt-5">
    <!-- Muestra el error -->
    <div class="alert alert-danger text-center p-4">

        <h3>❌ Error al guardar el turno</h3>

        <?php if ($error === "fecha_pasada"): ?>
            <p>La fecha del turno no puede ser anterior a hoy</p>

        <?php elseif ($error === "hora_pasada"): ?>
            <p>La hora seleccionada ya paso</p>

        <?php elseif ($error === "duplicado"): ?>
            <p>Ya existe un turno para esa fecha y hora</p>

        <?php else: ?>
            <p>No se pudo guardar el turno</p>
        <?php endif; ?>

        <!-- Boton para volver al listado -->
         
        <a href="index.php?controller=turno&action=list" class="btn btn-primary mt-3">
            Volver al listado
        </a>
    </div>
</div>