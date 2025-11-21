<?php

// Conexion a la base de datos
require_once 'config/config.php';

// Clase para la conexion a la base de datos
class Db {

// Atributos
    private $host;
    private $db;
    private $user;
    private $pass;
    public $conection;

// Constructor
    public function __construct(){
        
        $this->host = constant('DB_HOST');
        $this->db   = constant('DB');
        $this->user = constant('DB_USER');
        $this->pass = constant('DB_PASS');

    // Establecer la conexion
    try {
        $this->conection = new mysqli($this->host, $this->user, $this->pass, $this->db);
            if ($this->conection->connect_error) {
                die('Falló la conexión: ' . $this->conection->connect_error);
            }
            // Establecer el conjunto de caracteres
            $this->conection->set_charset("utf8mb4");
        }
    // Manejo de errores    
    catch(Exception $e){
            die("Error al conectar con la Base de Datos. Inténtelo más tarde.");
        }

    }

}
?>