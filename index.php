<?php
// Inicia sesión y carga configuración
session_start();
require_once 'config/config.php';

// Cargar permisos globales
require_once 'helpers/autorizacion.php';

// Validacion que bloquea todo sin login

$ctrl   = $_GET["controller"] ?? "";
$action = $_GET["action"] ?? "";


$logueado = !empty($_SESSION["usuarioID"]);
$esLogin  = ($ctrl === "usuario" && $action === "login");

if (!$logueado && !$esLogin) {
    header("Location:index.php?controller=usuario&action=login");
    exit;
}

//
// Inicializar variables
$rolID = $_SESSION["rolID"] ?? 0;

// Controladores disponibles segun rol
$controllers = $rolID > 0 ? ["paciente", "turno", "usuario", "rol"] : [];

// Controlador y acción e ID por defecto
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

    // Ejecutar la accion con o sin ID
    $result = $id ? $controllerObj->{$action}($id) : $controllerObj->{$action}();

    // Procesar resultados
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
?>