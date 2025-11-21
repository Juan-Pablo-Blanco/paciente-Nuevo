<?php
require_once 'model/db.php';

// Clase Paciente
class Paciente
{
    
    // Atributos
    private $tabla = 'pacientes';
    private $conection;
    private $campos;

    // Constructor
    public function __construct()
    {
        $this->campos = [
            "id" => "ID",
            "nombre" => "Nombre",
            "apellido" => "Apellido",
            "fecha_nacimiento" => "Fecha de Nacimiento",
            "telefono" => "Teléfono",
            "adulto_responsable" => "Adulto Responsable",
            "motivo_consulta" => "Motivo de Consulta"
        ];
    }

    //Get conexion
    private function getConection()
    {
        $dbObj = new Db();
        $this->conection = $dbObj->conection;

    }
    // Obtener todos los pacientes (para el select en turnos)
    public function getTabla()
    {
        $this->getConection();

        $sql = "SELECT 
                id, 
                nombre, 
                apellido, 
                fecha_nacimiento, 
                telefono, 
                adulto_responsable, 
                motivo_consulta
            FROM pacientes
            ORDER BY apellido, nombre";

        $resultado = $this->conection->query($sql);

        if (!$resultado) {
            echo "<p style='color:red'>❌ Error SQL en Paciente::getTabla(): " . $this->conection->error . "</p>";
            return [];
        }

        $data = $resultado->fetch_all(MYSQLI_ASSOC);

        return $data;
    }


    // Obtener un paciente por ID
    public function getTablaById($id)
    {
        if (is_null($id)) return false;
        $this->getConection();
        $sql = "SELECT a.*, COUNT(b.id) AS total_turnos
                FROM pacientes a
                LEFT JOIN turnos b ON a.id = b.paciente_id
                WHERE a.id = ?
                GROUP BY a.id";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param('i', $id);
        try {
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();
        } catch (Exception $e) {
            echo "Error al obtener paciente: " . $e->getMessage();
            return false;
        }
    }

    // Guardar paciente (crear o actualizar)
    public function save($param)
    {
        $this->getConection();

        $exists = false;
        $id = null;

        // Verificar si existe
        if (isset($param['id']) && $param['id'] != '') {
            $actual = $this->getTablaById($param['id']);
            if (isset($actual['id'])) {
                $exists = true;
                $id = $actual['id'];
            }
        }

        // Asignar valores recibidos
        $valores = [];
        foreach ($this->campos as $key => $value) {
            if ($key != 'id') {
                $valores[$key] = $param[$key] ?? '';
            }
        }

        if ($exists) {
            // UPDATE
            $sql = "UPDATE " . $this->tabla . " SET ";
            $types = '';
            $data = [];
            foreach ($valores as $key => $val) {
                $sql .= $key . " = ?, ";
                $types .= 's';
                $data[] = $val;
            }
            $sql = rtrim($sql, ', ') . " WHERE id = ?";
            $types .= 'i';
            $data[] = $id;

            $stmt = $this->conection->prepare($sql);
            $stmt->bind_param($types, ...$data);
            $stmt->execute();
        } else {
            // INSERT
            $sql = "INSERT INTO " . $this->tabla . " (" . implode(',', array_keys($valores)) . ") VALUES (";
            $sql .= rtrim(str_repeat('?,', count($valores)), ',') . ")";
            $types = str_repeat('s', count($valores));
            $data = array_values($valores);

            $stmt = $this->conection->prepare($sql);
            $stmt->bind_param($types, ...$data);
            $stmt->execute();
            $id = $this->conection->insert_id;
        }

        return $id;
    }

    // Borrar paciente
    public function deleteTablaById($id)
    {
        $this->getConection();
        $sql = "DELETE FROM " . $this->tabla . " WHERE id = ?";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param('i', $id);
        try {
            return $stmt->execute();
        } catch (Exception $e) {
            return "Error al borrar paciente: " . $e->getMessage();
        }
    }

    // Obtener campos
    public function getCampos()
    {
        return $this->campos;
    }


    // Verificar si un paciente tiene turnos asociados
    public function tieneTurnos($id)
    {
        $this->getConection();
        $sql = "SELECT COUNT(*) AS total FROM turnos WHERE paciente_id = ?";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();
        return $row['total'] > 0;
    }
}
