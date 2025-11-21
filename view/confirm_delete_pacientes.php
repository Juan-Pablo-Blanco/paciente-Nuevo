<div class="row">
	<form class="form"
		action="index.php?controller=<?= htmlspecialchars($_GET["controller"]) ?>&action=delete"
		method="POST">

		<?php
		// Datos del paciente
		$paciente = $dataToView["data"] ?? [];
		$id = $paciente["id"] ?? '';
		$nombre = $paciente["nombre"] ?? '';
		$apellido = $paciente["apellido"] ?? '';
		$relaciones = $dataToView["relaciones"] ?? []; // ejemplo: ['turnos']

		// Campos de la tabla (solo para mostrar detalle)
		$campos = $campos ?? [];
		?>

		<input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

		<?php if (!empty($relaciones)): ?>
			<!-- Tiene relaciones, no se puede eliminar -->
			<div class="alert alert-danger">
				<p><b>No es posible eliminar este paciente porque registra:</b></p>
				<ul>
					<?php foreach ($relaciones as $rel): ?>
						<li><?= htmlspecialchars($rel, ENT_QUOTES) ?></li>
					<?php endforeach; ?>
				</ul>
				
				<!-- Datos del paciente -->
				<p><b>Datos del paciente:</b></p>
				<?php foreach ($campos as $key => $encabezado): ?>
					<?php if (isset($paciente[$key])): ?>
						<p><b><?= htmlspecialchars($encabezado) ?>:</b> <?= htmlspecialchars($paciente[$key]) ?></p>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

		<?php else: ?>
			<!-- No tiene relaciones, se puede eliminar -->
			<div class="alert alert-warning">
				<p><b>¿Confirma que desea eliminar al paciente
						<?= htmlspecialchars($apellido . ', ' . $nombre) ?>?</b></p>

				<!-- Muestra los datos del paciente a eliminar -->
				<?php foreach ($campos as $key => $encabezado): ?>
					<?php if (isset($paciente[$key])): ?>
						<p><b><?= htmlspecialchars($encabezado) ?>:</b> <?= htmlspecialchars($paciente[$key]) ?></p>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<input type="submit" value="Eliminar" class="btn btn-danger" />
		<?php endif; ?>

		<!-- Boton para cancelar y volver al listado -->
		<a class="btn btn-primary"
			href="index.php?controller=<?= htmlspecialchars($_GET["controller"]) ?>&action=list">
			Cancelar
		</a>
	</form>
</div>