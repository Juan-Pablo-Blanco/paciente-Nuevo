<div class="row">
	<form class="form" action="index.php?controller=<?= $_GET["controller"] ?>&action=delete" method="POST">
		<input type="hidden" name="id" value="<?= $dataToView["data"]["id"] ?? '' ?>">

		<div class="alert alert-warning">
			<p><b>¿Confirma que desea eliminar este turno?</b></p>

			<?php
			if (isset($campos) && isset($dataToView["data"])) {
				foreach ($campos as $key => $encabezado) {
					if (isset($dataToView["data"][$key])) {
						echo "<p><b>" . htmlspecialchars($encabezado) . ":</b> " . htmlspecialchars($dataToView["data"][$key]) . "</p>";
					}
				}
			}
			?>
		</div>

		<input type="submit" value="Eliminar" class="btn btn-danger" />
		<a class="btn btn-primary" href="index.php?controller=<?= $_GET["controller"] ?>&action=list">Cancelar</a>
	</form>
</div>