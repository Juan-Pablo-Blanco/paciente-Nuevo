<div class="row">
	<!-- Formulario de confirmación de eliminación -->
	<form class="form" action="index.php?controller=<?= $_GET["controller"] ?>&action=delete" method="POST">
		<input type="hidden" name="id" value="<?= $dataToView["data"]["id"] ?? '' ?>">

		<!-- Mensajes de confirmacion y datos del turno -->
		<div class="alert alert-warning">
			<p><b>¿Confirma que desea eliminar este turno?</b></p>

			<?php
			// Muestra los datos del turno
			if (isset($campos) && isset($dataToView["data"])) {
				//Recorre los campos
				foreach ($campos as $key => $encabezado) {
					//Si el dato existe en $dataToView["data"], lo muestra
					if (isset($dataToView["data"][$key])) {
						echo "<p><b>" . htmlspecialchars($encabezado) . ":</b> " . htmlspecialchars($dataToView["data"][$key]) . "</p>";
					}
				}
			}
			?>
		</div>

		<!-- Botones de confirmacion y cancelacion -->
		<input type="submit" value="Eliminar" class="btn btn-danger" />
		<a class="btn btn-primary" href="index.php?controller=<?= $_GET["controller"] ?>&action=list">Cancelar</a>
	</form>
</div>