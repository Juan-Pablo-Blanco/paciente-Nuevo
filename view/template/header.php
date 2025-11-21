<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título dinamico segun el controller -->
    <title><?= $_GET["controller"] ?? '' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos propios -->
    <link href="view/template/estilos.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <!-- Navbar principal -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">

                <?php 
                    // Controller y accion actuales
                    $ctrl = $_GET["controller"] ?? '';
                    $action = $_GET["action"] ?? '';
                ?>

                <!-- Titulo de gestion dinamico -->
                <h3>
                    Gestión de <?= $ctrl . ($ctrl == "rol" ? "e" : "") ?>s
                </h3>

                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">

                        <!-- Boton Crear: solo aparece en la vista de lista -->
                        <li class="nav-item mx-3">
                            <?php if ($action == "list") { ?>
                                <!-- Si estamos en la vista de listado, mostrar boton de crear -->
                                <a href="index.php?controller=<?= $ctrl ?>&action=edit"
                                    class="btn btn-outline-primary">➕ Crear <?= $ctrl ?></a>
                            <?php } ?>
                        </li>

                        <!-- Botones de navegacion a otros controllers -->
                        <?php
                        if (!empty($controllers) && is_array($controllers)) {
                            foreach ($controllers as $txtBoton) {
                        ?>
                                <li class="nav-item mx-3">
                                    <!-- Boton de navegacion a otros controllers -->
                                    <a href="index.php?controller=<?= $txtBoton ?>&action=list"
                                        class="btn btn-outline-primary">
                                        <?= $txtBoton . ($txtBoton == "rol" ? "e" : "") ?>s
                                    </a>
                                </li>
                        <?php 
                            }
                        } 
                        ?>

                        <!-- Boton Login / Logout -->
                        <li class="nav-item mx-3">
                            <?php if (isset($_SESSION["usuario"])) { ?>
                                <!-- Si el usuario esta logueado, mostrar boton de logout con su nombre -->
                                <a href="index.php?controller=usuario&action=login&logout=true"
                                    class="btn btn-danger"><?= $_SESSION["usuario"] ?></a>
                            <?php } else { ?>
                                <!-- Si no hay usuario, mostrar boton de login -->
                                <a href="index.php?controller=usuario&action=login"
                                    class="btn btn-outline-primary">Ingresar</a>
                            <?php } ?>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <hr />