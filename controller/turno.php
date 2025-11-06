<?php
require_once 'model/turno.php';
require_once 'model/paciente.php';

class TurnoController
{
    public $page_title;
    public $view;
    public $tablaObj;
    private $tabla = "turnos";

    public function __construct()
    {
        $this->view = 'listar';
        $this->page_title = '';
        $this->tablaObj = new Turno();
    }

    // Listar turnos
    public function list()
	{
		$this->page_title = 'Listado de ' . $this->tabla;
		return $this->tablaObj->getTabla();
	}


    // Crear o editar turno
    public function edit($id = null)
    {
        $this->view = 'edit_turnos';
        $this->page_title = $id ? 'Editar turno' : 'Crear turno';

        // Datos del turno (si existe)
        $turnoData = $id ? $this->tablaObj->getTablaById($id) : [];

        // Traer todos los pacientes
        require_once 'model/Paciente.php';
        $pacienteModel = new Paciente();
        $pacientes = $pacienteModel->getTabla();

        return [
            'data' => $turnoData,
            'pacientes' => $pacientes
        ];
    }


    // Guardar turno (crear o actualizar)
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->tablaObj->save($_POST);
            header("Location: index.php?controller=turno&action=list");
            exit();
        }
    }

    // Muestra la confirmación de eliminación
    public function confirmDelete()
    {
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            echo "Error: ID no recibido";
            exit;
        }

        $this->view = 'confirm_delete_turnos';
        $this->page_title = 'Eliminar turno';

        $dataToView["data"] = $this->tablaObj->getTablaById($_GET["id"]);
        $dataToView["campos"] = [
            "id" => "ID",
            "paciente_id" => "Paciente",
            "fecha_turno" => "Fecha",
            "hora_turno" => "Hora",
            "observaciones" => "Observaciones",
            "obra_social" => "Obra Social"
        ];

        return $dataToView;
    }

    // Ejecuta la eliminación
    public function delete()
    {
        if (!isset($_POST["id"]) || empty($_POST["id"])) {
            echo "Error: ID no recibido";
            exit;
        }

        $result = $this->tablaObj->deleteTablaById($_POST["id"]);

        if ($result) {
            // Redirige al listado con mensaje de éxito
            header("Location: index.php?controller=turnos&action=list&response=true");
            exit;
        } else {
            // Redirige al listado con mensaje de error
            header("Location: index.php?controller=turnos&action=list&response=false");
            exit;
        }
    }
}
