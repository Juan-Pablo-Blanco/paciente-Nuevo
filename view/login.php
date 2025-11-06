<div class="form-control">
	<?php
	$usuario = "";
	if (isset($_POST["usuario"])) {
		$usuario = $_POST["usuario"];
		if ($dataToView["data"]) {
			session_start();
			$_SESSION["usuarioID"] = $dataToView["data"]["id"];
			$_SESSION["usuario"] = $dataToView["data"]["usuario"];
			$_SESSION["rolID"] = $dataToView["data"]["id_rol"];
			$_SESSION["rol"] = $dataToView["data"]["rol"];
			header("Location:index.php?controller=usuario&action=list");
			exit;
		} else {
		?>
			<div class="alert alert-danger">
				No existe Usuario o la Contraseña no es la correcta.
			</div>
	<?php
		}
	}
	if (isset($_GET["logout"])) {
		// Destruir todas las variables de sesión
		$_SESSION = array();
		// Si se desea destruir la cookie de sesión, también se debe hacer.
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]
			);
		}
		// Finalmente, destruir la sesión
		session_destroy();
		// Redireccionar al login
		header('Location: index.php?controller=usuario&action=login');
		exit;
	}
	?>
	<form class="form" action="index.php?controller=usuario&action=login" method="POST">
		<div class="form-container">
			<div class="form-group">
				<label class="fw-bold">Usuario</label>
				<input class="form-control" type="text"
					name="usuario" value="<?= $usuario ?>" />
			</div>
			<div class="form-group">
				<label class="fw-bold">Contraseña</label>
				<input class="form-control" type="password"
					name="password" value="" />
			</div>
			<p></p>
			<input type="submit" value="Ingresar" class="btn btn-primary" />
			<a class="btn btn-primary" href="index.php?controller=<?= $_GET["controller"] ?>&action=list">Cancelar</a>
	</form>
</div>
</div>