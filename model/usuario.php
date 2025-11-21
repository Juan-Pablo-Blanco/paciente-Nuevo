<?php

require_once 'model/db.php';

//Clase Usuario
class Usuario {

	//Atributos
	private $tabla = 'usuarios';
	private $conection;
	private $campos;

	 
	//Constructor
	public function __construct() {
		$this->campos= [
			"id"=>"ID",
			"usuario" => "Usuario",
			"nombre" => "Nombre",
			"apellido" => "Apellido",
			"email" => "Correo",
			"password" => "Contraseña",
			"id_rol"=>"Rol"
		];
	}

	// Set conexion
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	// Trae todos los usuarios
	public function getTabla(){
		$this->getConection();
		$sql = "SELECT a.*, b.rol 
		FROM ".$this->tabla. " a
		INNER JOIN roles b
		ON a.id_rol=b.id
		ORDER BY 1";
		$stmt = $this->conection->prepare($sql);
		try {
			$stmt->execute();
			$resultado = $stmt->get_result();
			return $resultado->fetch_all(MYSQLI_ASSOC);
		}
		catch (Exception $e){
			echo "Error al conectar con la Base de Datos";
			return false;
		}
	}

	// Trae un usuario por id
	public function getTablaById($id){
		if(is_null($id)) return false;
		$this->getConection();
		$sql = "SELECT a.*, b.rol
		 FROM usuarios a
		 INNER JOIN roles b
		 ON a.id_rol=b.id
		 WHERE a.id =  ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->bind_param('i', $id); // 'i' para entero
		$stmt->execute();
		$resultado = $stmt->get_result();
		return $resultado->fetch_assoc();
	}

	// Login
	public function login($param)
	{
		$this->getConection();
		$sql = "SELECT a.*, b.rol
		 FROM usuarios a
		 INNER JOIN roles b
		 ON a.id_rol=b.id
		 WHERE a.usuario =  ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->bind_param('s', $param["usuario"]);
		$stmt->execute();
		$resultado = $stmt->get_result();
		return $resultado->fetch_assoc();
	}

	// Save
	public function save($param){
    // Asegura que la conexion a la base de datos
    $this->getConection();

    // Inicializa la variable que indica si el registro ya existe
    $exists = false;

    // Si llega un ID en los parametros, revisa si el usuario ya existe
    if(isset($param["id"]) and $param["id"] !=''){
        // Obtiene los datos actuales del usuario por ID
        $actual = $this->getTablaById($param["id"]);
        if(isset($actual["id"])){
            $exists = true; // Marca que el usuario existe

            // Crea variables dinamicas con los valores actuales del registro
            foreach ($this->campos as $key => $value) {
                $$key = $actual[$key];
            }
        }
    }

    // Sobrescribe los valores actuales con los valores que llegan en $param
    foreach ($this->campos as $key => $value) {
        if (isset($param[$key])) $$key = $param[$key];
    }

    // Operaciones en la base de datos

    if($exists){
        // Si el registro existe hacemos un UPDATE
        $sql  = "UPDATE ".$this->tabla. " SET ";
        $data = [];

        // Recorre los campos de la tabla
        foreach ($this->campos as $key => $value) {
            if ($value !== "ID") { // No actualizamos la columna ID
                if (count($data) > 0) $sql .= ", ";
                $sql .= $key . " = ?";

                // Si es la contraseña, la hashea antes de guardar
                if ($value == "Contraseña") {
                    $$key = password_hash($$key, PASSWORD_BCRYPT);
                }

                $data[] = $$key; // Agrega el valor al array de parametros
            } else {
                $id = $$key; // Guarda el ID para la clausula WHERE
            }
        }

        $data[] = $id; // Agrega el ID al final para el WHERE
        $sql .= " WHERE id = ?"; // Clausula WHERE para actualizar el registro correcto
        $stmt = $this->conection->prepare($sql); // Prepara la consulta
        $res = $stmt->execute($data); // Ejecuta con los valores
    } else {
        // Si el registro no existe hacemos un INSERT
        $sql = "INSERT INTO ".$this->tabla." (";
        $data = [];

        // Construye la lista de columnas y valores
        foreach ($this->campos as $key => $value) {
            if (count($data) > 0) $sql .= ", "; 
            $sql .= $key;

            // Si es la contraseña, la hashea antes de guardar
            if ($value == "Contraseña") {
                $$key = password_hash($$key, PASSWORD_BCRYPT);
            }

            $data[] = $$key; // Agrega el valor al array de parametros
        }

        $sql .= ") VALUES ("; // Inicia la seccion VALUES

        // Agrega placeholders "?" según la cantidad de campos
        for ($i = 0; $i < count($data); $i++) {
            if ($i > 0) $sql .= ", ";
            $sql .= "?";
        }
        $sql .= ")";

        $stmt = $this->conection->prepare($sql); // Prepara la consulta
        $stmt->execute($data); // Ejecuta con los valores
        $id = $this->conection->insert_id; // Obtiene el ID generado
    }

    // Retorna el ID del registro insertado o actualizado
    return $id;  
}
	// Delete por ID
	public function deleteTablaById($id) {
		$this->getConection();
		$sql = "DELETE FROM ".$this->tabla. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		try {
			return $stmt->execute([$id]);
		}
		catch (Exception $e) {
			return "Error al borrar: " . $e->getMessage();
		}
	}

	//Trae los campos
	public function getCampos(){
		return $this->campos;
	}
}
?>