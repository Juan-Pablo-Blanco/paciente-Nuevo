<?php

require_once 'model/Paciente.php';

class PacienteController
{

	public $page_title;
	public $view;
	public $tablaObj;
	private $tabla = "pacientes";

	public function __construct()
	{
		$this->view = 'listar';
		$this->page_title = '';
		$this->tablaObj = new Paciente();
	}

	//Lista 
	public function list()
	{
		$this->page_title = 'Listado de ' . $this->tabla;

		$pacientes = $this->tablaObj->getTabla();

		if (empty($pacientes)) {
			echo "<div class='alert alert-warning m-2'>⚠️ No hay pacientes cargados en la base de datos.</div>";
		}

		return ["data" => $pacientes];
	}

	public function getCampos()
	{
		return [
			"id" => "ID",
			"nombre" => "Nombre",
			"apellido" => "Apellido",
			"fecha_nacimiento" => "Fecha de Nacimiento",
			"telefono" => "Teléfono",
			"adulto_responsable" => "Adulto Responsable",
			"motivo_consulta" => "Motivo de Consulta"
		];
	}

	// Crear o Editar
	 public function edit($id = null)
    {
        $this->view = 'edit_pacientes';
        $this->page_title = $id ? 'Editar paciente' : 'Crear paciente';

        $pacienteData = $id ? $this->tablaObj->getTablaById($id) : [];

        return ["data" => $pacienteData];
    }

    // Guardar (crear o actualizar)
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->tablaObj->save($_POST);
            header("Location: index.php?controller=paciente&action=list");
            exit();
        }
    }

    // Confirmar eliminación
    public function confirmDelete()
    {
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            echo "Error: ID no recibido";
            exit;
        }

        $this->view = 'confirm_delete_pacientes';
        $this->page_title = 'Eliminar paciente';

        $dataToView["data"] = $this->tablaObj->getTablaById($_GET["id"]);
        $dataToView["campos"] = $this->getCampos();

        return $dataToView;
    }

    // Eliminar
    public function delete()
    {
        if (!isset($_POST["id"]) || empty($_POST["id"])) {
            echo "Error: ID no recibido";
            exit;
        }

        $result = $this->tablaObj->deleteTablaById($_POST["id"]);

        header("Location: index.php?controller=paciente&action=list&response=" . ($result ? "true" : "false"));
        exit;
    }


	//verifica si el paciente tiene turnos asociados
	public function pacienteTieneTurnos($id)

	{
		return $this->tablaObj->tieneTurnos($id);
	}

}