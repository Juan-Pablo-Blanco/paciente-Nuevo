<?php

require_once 'model/db.php';

// Clase Rol
class Rol {

	//Atributos
	private $tabla = 'roles';
	private $conection;
	private $campos;

	//Constructor
	public function __construct() {
		$this->campos= [
			"id"=>"ID",
			"rol" => "Rol"
		];
	}

	// Set conexion
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	// Trae todos los roles
	public function getTabla(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->tabla;
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

	// Trae un rol por id
	public function getTablaById($id){
		if(is_null($id)) return false;
		$this->getConection();
		$sql = "SELECT * FROM roles WHERE id =  ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->bind_param('i', $id); // 'i' para entero
		$stmt->execute();
		$resultado = $stmt->get_result();
		return $resultado->fetch_assoc();
	}

	// Save
	public function save($param){
		$this->getConection();

		//Checkea si existe
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			$actual = $this->getTablaById($param["id"]);
			if(isset($actual["id"])){
				$exists = true;
				//Valores actuales
				foreach ($this->campos as $key => $value) {
					$$key = $actual[$key];
				}
			}
		}

		//Recibe valores
		foreach ($this->campos as $key => $value) {
			if (isset($param[$key])) $$key = $param[$key];
		}

		// Operaciones en la base de datos
		if($exists){
			$sql  = "UPDATE ".$this->tabla. " SET ";
			$data=[];
			foreach ($this->campos as $key => $value) {
				if ($value!=="ID") {
					if (count($data) > 0) $sql .= ", ";
					$sql .= $key . " = ?";
					$data[] = $$key;
				}
				else {
					$id= $$key;
				}
			}
			$data[] = $id;
			$sql .= " WHERE id = ?";
			$stmt = $this->conection->prepare($sql);
			try {
				$stmt->execute($data);
			} catch (Exception $e) {
				// Error al actualizar: Seguramente este rol ya existe
			}
		}else{
			//Insert
			$sql = "INSERT INTO ".$this->tabla." (";
			$data = [];
			foreach ($this->campos as $key => $value) {
				if (count($data) > 0) $sql .= ", ";
				$sql .= $key;
				$data[] = $$key;
			}
			$sql .= ") VALUES (";
			for ($i=0; $i<count($data);$i++){
				if ($i > 0) $sql .= ", ";
				$sql .= "?";
			}
			$sql .= ")";
			$stmt = $this->conection->prepare($sql);
			try {
				$stmt->execute($data);
				$id = $this->conection->insert_id;
			} catch (Exception $e) {
				//Error al insertar: Seguramente este rol ya existe
				$id=0;
			}
		}
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
			return "Error al borrar: Seguramente el rol tiene usuarios asociados. " . $e->getMessage();
		}
	}

	// Trae los campos
	public function getCampos(){
		return $this->campos;
	}
}
?>