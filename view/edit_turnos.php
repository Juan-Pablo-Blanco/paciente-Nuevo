<?php
// Inicializa variables con los datos del turno actual (si existe)
foreach ($dataToView['data'] ?? [] as $key => $value) {
    $$key = $value;
}

// Pacientes para el select (traídos desde el controlador)
$pacientes = $dataToView['pacientes'] ?? [];
?>

<div class="container mt-4">

    <?php if (isset($_GET["response"]) && $_GET["response"] === "true"): ?>
        <div class="alert alert-success">
            ✅ Operación realizada correctamente.
            <a href="index.php?controller=<?= htmlspecialchars($_GET["controller"] ?? '') ?>&action=list">
                Volver al listado
            </a>
        </div>
    <?php endif; ?>

    <form 
        action="index.php?controller=<?= htmlspecialchars($_GET["controller"] ?? '') ?>&action=save"
        method="POST"
        class="card p-4 shadow-sm"
    >
        <input type="hidden" name="id" value="<?= htmlspecialchars($id ?? '') ?>">

        <h4 class="mb-3"><?= isset($id) ? "Editar Turno" : "Nuevo Turno" ?></h4>

        <?php
        // Campos del formulario
        $camposTurnos = [
            'paciente_id'   => ['label' => 'Paciente', 'type' => 'select', 'options' => $pacientes],
            'fecha_turno'   => ['label' => 'Fecha del Turno', 'type' => 'date'],
            'hora_turno'    => ['label' => 'Hora del Turno', 'type' => 'time'],
            'obra_social'   => ['label' => 'Obra Social', 'type' => 'text'],
            'observaciones' => ['label' => 'Observaciones', 'type' => 'textarea']
        ];

        foreach ($camposTurnos as $key => $info): ?>
            <div class="mb-3">
                <label class="form-label fw-bold" for="<?= $key ?>">
                    <?= htmlspecialchars($info['label']) ?>
                </label>

                <?php if ($info['type'] === 'textarea'): ?>
                    <textarea
                        id="<?= $key ?>"
                        name="<?= $key ?>"
                        class="form-control"
                        rows="3"><?= htmlspecialchars($$key ?? '', ENT_QUOTES) ?></textarea>

                <?php elseif ($info['type'] === 'select'): ?>
                    <select id="<?= $key ?>" name="<?= $key ?>" class="form-select" required>
                        <option value="">Seleccione un paciente</option>
                        <?php foreach ($info['options'] as $pac): ?>
                            <option value="<?= htmlspecialchars($pac['id']) ?>"
                                <?= (isset($$key) && $$key == $pac['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pac['apellido'] . ', ' . $pac['nombre'], ENT_QUOTES) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                <?php else: ?>
                    <input
                        type="<?= $info['type'] ?>"
                        id="<?= $key ?>"
                        name="<?= $key ?>"
                        class="form-control"
                        value="<?= htmlspecialchars($$key ?? '', ENT_QUOTES) ?>"
                    >
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">💾 Guardar</button>
            <a href="index.php?controller=<?= htmlspecialchars($_GET["controller"] ?? '') ?>&action=list"
               class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
