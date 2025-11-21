<?php

// Configuración de la base de datos

define ('DB_HOST', 'localhost');
define('DB', 'paciente_user');
define ('DB_USER', 'root');
define ('DB_PASS', '');

//Controlador y action por defecto

define("DEFAULT_CONTROLLER", "usuario");
define("DEFAULT_ACTION", "login");

//Listado de controladores
$controllers=["paciente","turno","usuario","rol"];

//Controlador por defecto
header('Content-Type: text/html; charset=utf-8');