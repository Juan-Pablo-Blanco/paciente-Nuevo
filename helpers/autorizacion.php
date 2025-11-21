<?php

require_once('permisos.php');


if (!isset($_SESSION["rolID"])) {
    return;
}

$rolID = $_SESSION["rolID"];
$controller = $_GET["controller"] ?? "";
$action     = $_GET["action"] ?? "list";

// Excepciones
if ($controller === "" || ($controller === "usuario" && $action === "login")) {
    return;
}

// Tabla de permisos
$permisos = [
    1 => "*", // administrador
    9 => ["list", "view", "edit", "save", "create"], // editor
    10 => ["list", "view"], // usuario
];

// Si el rol no existe
if (!isset($permisos[$rolID])) {
    mostrarErrorPermisos();
    return;
}

// Permisos del rol
$permitido = $permisos[$rolID];

// Admin tiene todo permitido
if ($permitido === "*") {
    return;
}

// Acción permitida?
if (!in_array($action, $permitido)) {
    mostrarErrorPermisos();
    return;
}

//Mostrar Error Permisos
function mostrarErrorPermisos()
{
    $_SESSION["lastController"] = $_GET["controller"] ?? '';
    
    require_once 'view/template/header.php';
    require_once 'view/error_permisos.php';
    require_once 'view/template/footer.php';
    exit;
}