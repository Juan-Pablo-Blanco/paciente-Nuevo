<?php
// Recorre los campos definidos en $campos4
foreach ($campos as $key => $encabezado) {
	$$key = "";
	// Si el dato existe en $dataToView["data"], lo asigna (para modo edicion)
	if (isset($dataToView["data"][$key])) $$key = $dataToView["data"][$key];
}
?>
<div class="form-control">
	<?php
	// Muestra mensajes de respuesta luego de guardar
	if (isset($_GET["response"]) and $_GET["response"] === true) {
		if ($usuario == "") {

	?>
			<!-- Si el usuario vino vacio se interpreta como "ya existe" -->
			<div class="alert alert-danger">
				Error: el usuario ya existe.
			</div>
		<?php
		} else {
		?>
			<div class="alert alert-success">
				Operación realizada correctamente. <a href="index.php?controller=<?= $_GET["controller"] ?>&action=list">Volver al listado</a>
			</div>
	<?php
		}
	}
	?>
	<!-- Formulario que envia los datos al metodo save del controlador actual -->
	<form class="form" action="index.php?controller=<?= $_GET["controller"] ?>&action=save" method="POST">
		<input type="hidden" name="id" value="<?= $id ?? '' ?>" />
		<div class="form-container">
			<?php
			foreach ($campos as $key => $encabezado) {
				if ($encabezado !== "" && $encabezado !== "ID") {
			?>
					<div class="form-group">
						<label class="fw-bold"><?= $encabezado ?></label>
						<?php
						if ($encabezado !== "Rol") {
						?>
							<input class="form-control"
								<?php if ($encabezado == "Email") { ?>
								type="email"
								<?php } else if ($encabezado == "Contraseña") { ?>
								type="password"
								<?php } else { ?>
								type="text"
								<?php } ?>
								name="<?= $key ?>" value="<?= $$key ?>" />
						<?php
						} else {
						?>
							<!-- Si el campo es Rol, renderiza un select -->
							<select name="<?= $key ?>" id="<?= $key ?>" required>
								<?php
								// Recorre la lista de roles 
								foreach ($dataToView["dataRel1"] as $rel1) {
								?>
									<option value="<?= $rel1["id"] ?>" <?= $rel1["id"] == $$key ? "selected" : "" ?>>
										<?= $rel1["rol"] ?>
									</option>
								<?php } ?>
							</select>
				<?php
						}
						echo "</div>";
					}
				}
				?><p></p>
				<!-- Botones de guardar y cancelar -->
				<input type="submit" value="Guardar" class="btn btn-primary" />
				<a class="btn btn-primary" href="index.php?controller=<?= $_GET["controller"] ?>&action=list">Cancelar</a>
	</form>
</div>
</div>