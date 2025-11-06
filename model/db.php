<?php

// Conexión a la base de datos
require_once 'config/config.php';

// Clase para la conexión a la base de datos
class Db {

// Atributos de la clase
    private $host;
    private $db;
    private $user;
    private $pass;
    public $conection;

// Constructor de la clase
    public function __construct(){
        
        $this->host = constant('DB_HOST');
        $this->db   = constant('DB');
        $this->user = constant('DB_USER');
        $this->pass = constant('DB_PASS');

    try {
        $this->conection = new mysqli($this->host, $this->user, $this->pass, $this->db);
            if ($this->conection->connect_error) {
                die('Falló la conexión: ' . $this->conection->connect_error);
            }
        }
    catch(Exception $e){
            die("Error al conectar con la Base de Datos. Inténtelo más tarde.");
        }

    }

}
?>