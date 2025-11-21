<div class="row">
	<!-- Si el modelo/controlador es 1, se ha eliminado correctamente -->
	<?php if ($dataToView["data"] === "1" || $dataToView["data"] === 1): ?>
		<!-- Muestra mensaje de exito -->
		<div class="alert alert-success">
			<?= htmlspecialchars($_GET["controller"]) ?> eliminado correctamente.
			<br>
			<!-- Boton para volver al listado -->
			<a class="btn btn-primary mt-2" href="index.php?controller=<?= htmlspecialchars($_GET["controller"]) ?>&action=list">
				Volver al listado
			</a>
		</div>
	<?php else: ?>
		<!-- Si no se ha eliminado, muestra el error -->
		<div class="alert alert-danger">
			<?= htmlspecialchars($_GET["controller"]) ?> <b>NO</b> eliminado.
			
			<?php 
			$mensaje = $dataToView["data"];

			// Si es array, mostrarlo en detalle
			if (is_array($mensaje)) {
				echo "<ul>";
				foreach ($mensaje as $key => $valor) {
					echo "<li><b>" . htmlspecialchars($key) . ":</b> " . htmlspecialchars($valor) . "</li>";
				}
				echo "</ul>";
			} else {
				// Si es string o mensaje de error
				echo "<p>" . htmlspecialchars($mensaje) . "</p>";
			}
			?>

			<!-- Boton para volver al listado -->
			<a class="btn btn-primary mt-2" href="index.php?controller=<?= htmlspecialchars($_GET["controller"]) ?>&action=list">
				Volver al listado
			</a>
		</div>
	<?php endif; ?>
</div>