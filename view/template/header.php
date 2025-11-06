<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_GET["controller"] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="view/template/estilos.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <h3>Gestión de <?= $_GET["controller"].($_GET["controller"]=="rol"?"e":"") ?>s</h3>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item mx-3">
                            <?php if ($_GET["action"] == "list") { ?>
                                <a href="index.php?controller=<?= $_GET["controller"] ?>&action=edit"
                                    class="btn btn-outline-primary">➕Crear <?= $_GET["controller"] ?></a>
                            <?php } else if ($_GET["controller"] !== "venta_detalle") {
                            ?> <a href="index.php?controller=<?= $_GET["controller"] ?>&action=list"
                                    class="btn btn-outline-primary">volver</a>
                            <?php
                            }
                            foreach ($controllers as $txtBoton) {
                            ?>
                        </li>
                        <li class="nav-item mx-3">
                            <a href="index.php?controller=<?= $txtBoton ?>&action=list"
                                class="btn btn-outline-primary"><?= $txtBoton.($txtBoton=="rol"?"e":"") ?>s</a>
                        <?php
                            }
                        ?>
                        </li>
                        <li class="nav-item mx-3">
                            <?php
                            if (isset($_SESSION["usuario"])) {
                            ?>
                                <a href="index.php?controller=usuario&action=login&logout=true"
                                    title="cerrar sesión" class="btn btn-danger"><?= $_SESSION["usuario"] ?></a>
                            <?php
                            } else {
                            ?>
                                <a href="index.php?controller=usuario&action=login"
                                    class="btn btn-outline-primary">ingresar</a>
                            <?php
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <hr />