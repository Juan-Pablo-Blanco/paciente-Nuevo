<?php
foreach ($campos as $key => $encabezado) {
	$$key = "";
	if (isset($dataToView["data"][$key])) $$key = $dataToView["data"][$key];
}
?>
<div class="form-control">
	<?php
	if (isset($_GET["response"]) and $_GET["response"] === true) {
	?>
		<div class="alert alert-success">
			Operación realizada correctamente. <a href="index.php?controller=<?= $_GET["controller"] ?>&action=list">Volver al listado</a>
		</div>
	<?php
	}
	?>

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
							<select name="<?= $key ?>" id="<?= $key ?>" required>
								<?php foreach ($dataToView["dataRel1"] as $rel1) { ?>
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
				<input type="submit" value="Guardar" class="btn btn-primary" />
				<a class="btn btn-primary" href="index.php?controller=<?= $_GET["controller"] ?>&action=list">Cancelar</a>
	</form>
</div>
</div>