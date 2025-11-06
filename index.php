<?php
session_start();

$rolID = 0;
require_once 'config/config.php';

// Verificar rol y acción por defecto
if (isset($_SESSION["rolID"])) {
        $rolID = $_SESSION["rolID"];
}

/*if ($rolID == 0) {
        $_GET["controller"] = "usuario";
        $_GET["action"] = "login";
        $controllers = [];
}
*/
// Controlador y acción por defecto
if (!isset($_GET["controller"])) $_GET["controller"] = constant("DEFAULT_CONTROLLER");
if (!isset($_GET["action"])) $_GET["action"] = constant("DEFAULT_ACTION");

// Ruta del controlador
$controller_path = 'controller/' . $_GET["controller"] . '.php';

// Si no existe, usar el controlador por defecto
if (!file_exists($controller_path)) {
        $controller_path = 'controller/' . constant("DEFAULT_CONTROLLER") . '.php';
}

// Cargar controlador
require_once $controller_path;
$controllerName = $_GET["controller"] . 'Controller';
$controller = new $controllerName();

// Inicializar datos de vista
$dataToView = [
        "data" => [],
        "dataRel1" => []
];

// Ejecutar acción del controlador
if (method_exists($controller, $_GET["action"])) {
        $result = $controller->{$_GET["action"]}();

        // 🔹 Si devuelve un array plano, lo guardamos en "data"
        if (is_array($result) && isset($result[0])) {
                $dataToView["data"] = $result;
        }
        // 🔹 Si devuelve un array asociativo (como edit o save), lo mezclamos
        elseif (is_array($result)) {
                $dataToView = array_merge($dataToView, $result);
        }

        // Cargar campos de la tabla actual
        if (method_exists($controller, 'getCampos')) {
                $campos = $controller->getCampos();
        }
        
        // Tablas relacionadas si aplica
        if (in_array($_GET["controller"], ["usuario", "turno", "rol", "paciente"]) && $_GET["action"] !== "list") {
                if (method_exists($controller, 'getTablaRel1')) {
                        $dataToView["dataRel1"] = $controller->getTablaRel1();
                }
        }
}

// Cargar vistas
require_once 'view/template/header.php';
require_once 'view/' . $controller->view . '.php';
require_once 'view/template/footer.php';

