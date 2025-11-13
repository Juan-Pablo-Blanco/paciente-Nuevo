<?php
session_start();
require_once 'config/config.php';


// Verificar sesión

if (
    (!isset($_SESSION["usuarioID"]) || empty($_SESSION["usuarioID"])) &&
    (!isset($_GET["controller"]) || $_GET["controller"] !== "usuario") &&
    (!isset($_GET["action"]) || $_GET["action"] !== "login")
) {
    header("Location: index.php?controller=usuario&action=login");
    exit;
}


//  Inicializar variables

$rolID = $_SESSION["rolID"] ?? 0;

// Controladores disponibles según rol
$controllers = $rolID > 0 ? ["paciente", "turno", "usuario", "rol"] : [];

// Controlador y acción por defecto
$controller = $_GET["controller"] ?? constant("DEFAULT_CONTROLLER");
$action = $_GET["action"] ?? constant("DEFAULT_ACTION");
$id = $_GET["id"] ?? null;


// Ruta del controlador

$controller_path = 'controller/' . $controller . 'Controller.php';
if (!file_exists($controller_path)) {
    $controller_path = 'controller/' . $controller . '.php';
}
if (!file_exists($controller_path)) {
    die("❌ Error: No se encontró el archivo del controlador en <b>$controller_path</b>");
}


// Cargar controlador y ejecutar acción

require_once $controller_path;

$controllerName = ucfirst($controller) . 'Controller';
if (!class_exists($controllerName)) {
    $controllerName = ucfirst($controller);
}

$controllerObj = new $controllerName();

$dataToView = ["data" => [], "dataRel1" => []];

if (method_exists($controllerObj, $action)) {
    // Ejecutar la acción con o sin ID
    $result = $id ? $controllerObj->{$action}($id) : $controllerObj->{$action}();

    if (is_array($result)) {
        if (isset($result["data"]) || isset($result["dataRel1"])) {
            $dataToView = array_merge($dataToView, $result);
        } else {
            $dataToView["data"] = $result;
        }
    }

    // Cargar los nombres de campos si el controlador los define
    $campos = method_exists($controllerObj, 'getCampos') ? $controllerObj->getCampos() : [];

    // Cargar tabla relacionada si aplica
    if (
        in_array($controller, ["usuario", "turno", "paciente", "rol"]) &&
        $action !== "list" &&
        empty($dataToView["dataRel1"]) &&
        method_exists($controllerObj, 'getTablaRel1')
    ) {
        $dataToView["dataRel1"] = $controllerObj->getTablaRel1();
    }
} else {
    die("❌ Error: La acción <b>$action</b> no existe en el controlador <b>$controllerName</b>.");
}


// Cargar las vistas

require_once 'view/template/header.php';
require_once 'view/' . $controllerObj->view . '.php';
require_once 'view/template/footer.php';
