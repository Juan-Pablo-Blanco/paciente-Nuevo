<div class="row">
	<form class="form" action="index.php?controller=<?= $_GET["controller"] ?>&action=delete" method="POST">
		<input type="hidden" name="id" value="<?= $dataToView["data"]["id"] ?>">
		<div class="alert alert-warning">
			<p><b>¿Confirma que desea eliminar este/a <?= $_GET["controller"] ?>?</b></p>
			<?php
			foreach ($campos as $key => $encabezado) {
				echo "<p><b>" . $encabezado . ":</b>" . $dataToView["data"][$key] . "</p>";
			}
			?>
		</div>
		<input type="submit" value="Eliminar" class="btn btn-danger" />
		<a class="btn btn-primary" href="index.php?controller=<?= $_GET["controller"] ?>&action=list">Cancelar</a>
	</form>
</div>