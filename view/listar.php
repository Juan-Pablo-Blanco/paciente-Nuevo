<div class="container mt-4">
	<!-- Titulo de la pagina lo trae el controlador -->
	<h2 class="mb-3 text-center"><?= htmlspecialchars($controller->page_title ?? 'Listado') ?></h2>

	<table class="table table-striped table-hover">
		<thead class="table-dark">
			<tr>
				<?php if (!empty($campos) && is_array($campos)): ?>
					<?php foreach ($campos as $key => $encabezado): ?>
						<!-- Se muestran solo los campos validos. No ID ni Contraseña -->
						<?php if ($encabezado !== "" && $encabezado !== "ID" && $encabezado !== "Contraseña"): ?>
							<th><?= htmlspecialchars($encabezado) ?></th>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<!-- Acciones Editar y Eliminar -->
				<th colspan="2">Acciones</th>
			</tr>
		</thead>

		<tbody>
			<?php if (!empty($dataToView["data"]) && is_array($dataToView["data"])): ?>
				<!-- Recorre la fila envidada por el controlador -->
				<?php foreach ($dataToView["data"] as $fila): ?>
					<tr>
						<?php
						$id = null;
						foreach ($campos as $key => $encabezado):
							if ($encabezado === "ID") {
								$id = $fila[$key] ?? null;
								continue;
							}
							// No mostrar contraseña ni campos vacios
							if ($encabezado === "Contraseña" || $encabezado === "") continue;
						?>
							<td>
								<?php
								if ($key === "paciente_id") {
									echo isset($fila["paciente"])
										? htmlspecialchars($fila["paciente"])
										: "(" . htmlspecialchars($fila[$key] ?? '') . ")";
								} else {
									echo htmlspecialchars($fila[$key] ?? '');
								}
								?>
							</td>
						<?php endforeach; ?>
						
						<!-- Boton editar -->
						<td>
							<a href="index.php?controller=<?= htmlspecialchars($_GET["controller"]) ?>&action=edit&id=<?= htmlspecialchars($id) ?>"
								class="btn btn-primary btn-sm">Editar</a>
						</td>
						<!-- Boton eliminar -->
						<td>
							<a href="index.php?controller=<?= htmlspecialchars($_GET["controller"]) ?>&action=confirmDelete&id=<?= htmlspecialchars($id) ?>"
								class="btn btn-danger btn-sm">Eliminar</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<!-- Si no hay registros, "actualmente no existen registros" -->
				<tr>
					<td colspan="<?= count($campos ?? []) + 2 ?>" class="text-center">
						<div class="alert alert-info m-0">
							Actualmente no existen registros de <?= htmlspecialchars($_GET["controller"]) ?>.
						</div>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>