<?php
// Inicializar variables a partir de $dataToView
foreach ($campos as $key => $encabezado) {
    $$key = $dataToView["data"][$key] ?? '';
}
?>

<div class="form-control">

    <?php if (isset($_GET["response"]) && $_GET["response"] === true) : ?>
        <div class="alert alert-success">
            Operación realizada correctamente. 
            <a href="index.php?controller=<?= htmlspecialchars($_GET["controller"] ?? '') ?>&action=list">Volver al listado</a>
        </div>
    <?php endif; ?>

    <form class="form" action="index.php?controller=<?= htmlspecialchars($_GET["controller"] ?? '') ?>&action=save" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id ?? '') ?>" />
        <div class="form-container">

            <?php
            // Definimos los campos de la tabla pacientes con su tipo
            $camposPacientes = [
                'nombre' => ['label' => 'Nombre', 'type' => 'text'],
                'apellido' => ['label' => 'Apellido', 'type' => 'text'],
                'fecha_nacimiento' => ['label' => 'Fecha de Nacimiento', 'type' => 'date'],
                'telefono' => ['label' => 'Teléfono', 'type' => 'tel'],
                'adulto_responsable' => ['label' => 'Adulto Responsable', 'type' => 'text'],
                'motivo_consulta' => ['label' => 'Motivo de Consulta', 'type' => 'text']
            ];

            foreach ($camposPacientes as $key => $info) :
            ?>
                <div class="form-group">
                    <label class="fw-bold" for="<?= $key ?>"><?= $info['label'] ?></label>
                    <input id="<?= $key ?>" class="form-control" type="<?= $info['type'] ?>" name="<?= $key ?>" value="<?= htmlspecialchars($$key, ENT_QUOTES) ?>" />
                </div>
            <?php endforeach; ?>

            <p></p>
            <input type="submit" value="Guardar" class="btn btn-primary" />
            <a class="btn btn-secondary" href="index.php?controller=<?= htmlspecialchars($_GET["controller"] ?? '') ?>&action=list">Cancelar</a>
        </div>
    </form>
</div>