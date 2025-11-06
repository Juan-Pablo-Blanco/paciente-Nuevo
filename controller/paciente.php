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
		return $this->tablaObj->getTabla();
	}

	// Crear o Editar
	public function edit($id = null)
	{
		$this->page_title = 'Editar ' . $this->tabla;
		$this->view = 'edit_' . $this->tabla;
		if (isset($_GET["id"])) {
			$id = $_GET["id"];
		} else {
			$this->page_title = 'Crear ' . $this->tabla;
		}
		return $this->tablaObj->getTablaById($id);
	}

	// Crear o Actualizar
	public function save()
	{
		$this->view = 'edit_' . $this->tabla;
		$this->page_title = 'Editar ' . $this->tabla;
		$id = $this->tablaObj->save($_POST);
		$result = $this->tablaObj->getTablaById($id);
		$_GET["response"] = true;
		return $result;
	}

	// Confirmar Borrado
	public function confirmDelete()
	{
		$this->page_title = 'Eliminar ' . $this->tabla;
		$this->view = 'confirm_delete_pacientes';

		$id = $_GET["id"];
		$paciente = $this->tablaObj->getTablaById($id);

		// Verificar relaciones (ejemplo: turnos)
		$relaciones = [];
		$turnosCount = $paciente['total_turnos'] ?? 0;
		if ($turnosCount > 0) {
			$relaciones[] = 'turnos';
		}
		return [
			"data" => $paciente,
			"relaciones" => $relaciones
		];
	}

	//Borrar
	public function delete()
	{
		$this->page_title = 'Listado de ' . $this->tabla;
		$this->view = 'delete';
		return $this->tablaObj->deleteTablaById($_POST["id"]);
	}

	//Campos con su descripción
	public function getCampos()
	{
		return $this->tablaObj->getCampos();
	}

	//verifica si el paciente tiene turnos asociados
	public function pacienteTieneTurnos($id)
	
	{
		return $this->tablaObj->tieneTurnos($id);
	}
}
