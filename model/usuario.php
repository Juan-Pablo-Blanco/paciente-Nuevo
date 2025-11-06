<?php

require_once 'model/db.php';

class Usuario {

	private $tabla = 'usuarios';
	private $conection;
	private $campos;

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

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all */
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

	/* Get by id */
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

	/* Get by usuario */
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

	/* Save */
	public function save($param){
		$this->getConection();

		/* Check if exists */
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			$actual = $this->getTablaById($param["id"]);
			if(isset($actual["id"])){
				$exists = true;
				/* Actual values */
				foreach ($this->campos as $key => $value) {
					$$key = $actual[$key];
				}
			}
		}

		/* Received values */
		foreach ($this->campos as $key => $value) {
			if (isset($param[$key])) $$key = $param[$key];
		}

		/* Database operations */
		if($exists){
			$sql  = "UPDATE ".$this->tabla. " SET ";
			$data=[];
			foreach ($this->campos as $key => $value) {
				if ($value!=="ID") {
					if (count($data) > 0) $sql .= ", ";
					$sql .= $key . " = ?";
					if ($value == "Contraseña") {
						$$key=password_hash($$key,PASSWORD_BCRYPT);
					}
					$data[] = $$key;
				}
				else {
					$id= $$key;
				}
			}
			$data[] = $id;
			$sql .= " WHERE id = ?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute($data);
		}else{
			$sql = "INSERT INTO ".$this->tabla." (";
			$data = [];
			foreach ($this->campos as $key => $value) {
				if (count($data) > 0) $sql .= ", ";
				$sql .= $key;
				if ($value == "Contraseña") {
					$$key = password_hash($$key, PASSWORD_BCRYPT);
				}
				$data[] = $$key;
			}
			$sql .= ") VALUES (";
			for ($i=0; $i<count($data);$i++){
				if ($i > 0) $sql .= ", ";
				$sql .= "?";
			}
			$sql .= ")";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute($data);
			$id = $this->conection->insert_id;
		}

		return $id;	

	}

	/* Delete by id */
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

	public function getCampos(){
		return $this->campos;
	}
}
?>