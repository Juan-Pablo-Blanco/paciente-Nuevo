<table class="table">
	<thead class="table-dark">
		<tr>
			<?php
			foreach ($campos as $key => $encabezado) {
				if ($encabezado !== "" && $encabezado !== "ID" && $encabezado !== "Contraseña") {
					echo "<th>" . htmlspecialchars($encabezado) . "</th>";
				}
			}
			?>
			<th colspan="2">Funciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($dataToView["data"] as $tabla): ?>
			<tr>
				<?php
				foreach ($campos as $key => $encabezado) {
					if ($encabezado !== "" && $encabezado !== "ID" && $encabezado !== "Contraseña") {
						echo "<td>";

						// Mostrar nombre del paciente si existe
						if ($key == "paciente_id") {
							// Se espera que el modelo Turno traiga el alias 'paciente' (JOIN con pacientes)
							echo isset($tabla["paciente"])
								? htmlspecialchars($tabla["paciente"])
								: "(" . htmlspecialchars($tabla[$key]) . ")";
						} else {
							echo isset($tabla[$key]) ? htmlspecialchars($tabla[$key]) : '';
						}

						echo "</td>";
					} elseif ($encabezado == "ID") {
						$id = $tabla[$key] ?? null;
					}
				}
				?>
				<td>
					<a href="index.php?controller=<?= htmlspecialchars($_GET["controller"]) ?>&action=edit&id=<?= htmlspecialchars($id) ?>" class="btn btn-primary">Editar</a>
				</td>
				<td>
					<a href="index.php?controller=<?= htmlspecialchars($_GET["controller"]) ?>&action=confirmDelete&id=<?= htmlspecialchars($id) ?>" class="btn btn-danger">Eliminar</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php if (count($dataToView["data"]) == 0): ?>
	<div class="alert alert-info">
		Actualmente no existen <?= htmlspecialchars($_GET["controller"]) ?>s.
	</div>
<?php endif; ?>
