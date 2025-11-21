<?php
// Conexion a la base de datos
require_once 'model/db.php';

// Definicion de la clase Turno
class Turno
{

	//Atributos
	private $tabla = 'turnos';
	private $conection;
	private $campos;

	//Constructor
	public function __construct()
	{
		$this->campos = [
			"id" => "ID",
			"paciente_id" => "Paciente",
			"fecha_turno" => "Fecha Turno",
			"hora_turno" => "Hora Turno",
			"observaciones" => "Observaciones",
			"obra_social" => "Obra Social"
		];
	}

	//Set conexion
	public function getConection()
	{
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	// Obtener toda la tabla
	public function getTabla()
	{
		$this->getConection();
		$sql = "SELECT 
                t.id,
                t.paciente_id,
                CONCAT(p.apellido, ', ', p.nombre) AS paciente,
                t.fecha_turno,
                t.hora_turno,
                t.obra_social,
                t.observaciones
            FROM turnos t
            LEFT JOIN pacientes p ON t.paciente_id = p.id
            ORDER BY t.fecha_turno DESC, t.hora_turno ASC";

		try {
			$stmt = $this->conection->prepare($sql);
			if (!$stmt) {
				// Muestra el error SQL real si el prepare falla
				die("Error en prepare(): " . $this->conection->error);
			}

			$stmt->execute();
			$resultado = $stmt->get_result();
			$turnos = $resultado->fetch_all(MYSQLI_ASSOC);

			// Para depuración, mostrás cuántos turnos encontró
			//if (empty($turnos)) {
			//	echo "<div class='alert alert-warning m-2'>⚠️ No hay turnos cargados en la base de datos.</div>";
			//}

			return $turnos;
		} catch (Exception $e) {
			echo "<div class='alert alert-danger'>Error al obtener turnos: " . $e->getMessage() . "</div>";
			return [];
		}
	}


	// Obtener tabla por id
	public function getTablaById($id)
	{
		$this->getConection();
		$sql = "SELECT t.*, p.nombre AS paciente
            FROM turnos t
            LEFT JOIN pacientes p ON t.paciente_id = p.id
            WHERE t.id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$resultado = $stmt->get_result();
		return $resultado->fetch_assoc();
	}

	//Save
	public function save($param)
	{
		$this->getConection();

		//Checkea si existe
		$exists = false;
		if (isset($param["id"]) and $param["id"] != '') {
			$actual = $this->getTablaById($param["id"]);
			if (isset($actual["id"])) {
				$exists = true;
				//Valores actuales
				foreach ($this->campos as $key => $value) {
					$$key = $actual[$key];
				}
			}
		}

		//Valores recibidos
		foreach ($this->campos as $key => $value) {
			if (isset($param[$key])) $$key = $param[$key];
		}

		//Operaciones en la base de datos
		if ($exists) {
			$sql  = "UPDATE " . $this->tabla . " SET ";
			$data = [];
			foreach ($this->campos as $key => $value) {
				if ($value !== "ID") {
					if (count($data) > 0) $sql .= ", ";
					$sql .= $key . " = ?";
					$data[] = $$key;
				} else {
					$id = $$key;
				}
			}
			$data[] = $id;
			$sql .= " WHERE id = ?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute($data);
		} else {
			//Insert
			$sql = "INSERT INTO " . $this->tabla . " (";
			$data = [];
			foreach ($this->campos as $key => $value) {
				if (count($data) > 0) $sql .= ", ";
				$sql .= $key;
				$data[] = $$key;
			}
			$sql .= ") VALUES (";
			for ($i = 0; $i < count($data); $i++) {
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

	//Borrar por id
	public function deleteTablaById($id)
	{
		$this->getConection();
		$sql = "DELETE FROM " . $this->tabla . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		try {
			return $stmt->execute([$id]);
		} catch (Exception $e) {
			return "Error al borrar: Seguramente el paciente tiene turnos asociadas. " . $e->getMessage();
		}
	}

	//Trae los campos
	public function getCampos()
	{
		return $this->campos;
	}
}
