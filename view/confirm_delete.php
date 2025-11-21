<div class="row">
	<!-- Formulario de confirmacion de eliminacion -->
	<form class="form" action="index.php?controller=<?= $_GET["controller"] ?>&action=delete" method="POST">
		<input type="hidden" name="id" value="<?= $dataToView["data"]["id"] ?>">

		<!-- Mensaje de advertencia -->
		<div class="alert alert-warning">
			<!-- Muestra los datos del paciente a eliminar -->
			<p><b>¿Confirma que desea eliminar este/a <?= $_GET["controller"] ?>?</b></p>
			<?php
			foreach ($campos as $key => $encabezado) {
				echo "<p><b>" . $encabezado . ":</b>" . $dataToView["data"][$key] . "</p>";
			}
			?>
		</div>
		<!-- Botones de confirmacion y cancelacion -->
		<input type="submit" value="Eliminar" class="btn btn-danger" />
		<a class="btn btn-primary" href="index.php?controller=<?= $_GET["controller"] ?>&action=list">Cancelar</a>
	</form>
</div>