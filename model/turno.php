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

		
		//Detectar si es edicion o alta
		

		$exists = false;
		$id = 0;  // Inicializar ID

		if (!empty($param["id"])) {
			$actual = $this->getTablaById($param["id"]);

			if (!empty($actual["id"])) {
				$exists = true;
				$id = $actual["id"];

				// Cargo valores actuales
				foreach ($this->campos as $key => $value) {
					$$key = $actual[$key];
				}
			}
		}

		
		//Cargar valores recibidos del formulario
		

		foreach ($this->campos as $key => $value) {
			if (isset($param[$key])) $$key = $param[$key];
		}

		// Normalizar 
		$fecha_turno = trim($fecha_turno);
		$hora_turno  = trim($hora_turno);

		$fecha_actual = date("Y-m-d");
		$hora_actual  = date("H:i");

		
		// Validacion de fecha pasada
		

		if (strtotime($fecha_turno) < strtotime($fecha_actual)) {
			return ["error" => "fecha_pasada"];
		}

		
		// Validacion de hora ya pasada (si es hoy)
		

		if ($fecha_turno == $fecha_actual && strtotime($hora_turno) <= strtotime($hora_actual)) {
			return ["error" => "hora_pasada"];
		}

		
		//Validacion de duplicado
	

		// Si estoy editando: excluir mi propio ID
		$check_id = $id > 0 ? $id : 0;

		$sql = "SELECT id FROM turnos
            WHERE fecha_turno = ?
            AND hora_turno  = ?
            AND id != ?";

		$stmt = $this->conection->prepare($sql);
		$stmt->bind_param("ssi", $fecha_turno, $hora_turno, $check_id);
		$stmt->execute();

		$result = $stmt->get_result();

		if ($result->fetch_assoc()) {
			return ["error" => "duplicado"];
		}

		
		//Guardar (UPDATE o INSERT)
		
		if ($exists) {

			// UPDATE
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
			$stmt->execute($data);
		} else {

			// INSERT
			$sql = "INSERT INTO " . $this->tabla . " (";
			$data = [];

			foreach ($this->campos as $key => $value) {
				if (count($data) > 0) $sql .= ", ";
				$sql .= $key;
				$data[] = $$key;
			}

			$sql .= ") VALUES (" . str_repeat("?, ", count($data) - 1) . "?)";

			$stmt = $this->conection->prepare($sql);
			$stmt->execute($data);

			$id = $this->conection->insert_id;
		}

		return ["ok" => true];
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
