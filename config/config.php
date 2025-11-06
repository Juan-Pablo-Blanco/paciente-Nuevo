<?php

// Configuración de la base de datos

define ('DB_HOST', 'localhost');
define('DB', 'paciente_user');
define ('DB_USER', 'root');
define ('DB_PASS', '');

define("DEFAULT_CONTROLLER", "paciente");
define("DEFAULT_ACTION", "list");
$controllers=["paciente","turno","usuario","rol"];