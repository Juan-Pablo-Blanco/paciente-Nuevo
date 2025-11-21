<?php

require_once 'model/usuario.php';
require_once 'model/rol.php';

// Se crea la clase UsuarioController
class UsuarioController
{
	// Atributos
	public $page_title;
	public $view;
	private $tabla = "usuarios";
	public $tablaObj;
	public $tablaObj1;

	// Constructor
	public function __construct()
	{
		$this->view = 'listar';
		$this->page_title = '';
		$this->tablaObj = new Usuario();
		$this->tablaObj1 = new Rol();
	}

	// Listar
	public function list()
	{
		$this->page_title = 'Listado de ' . $this->tabla;
		return ["data" => $this->tablaObj->getTabla()];
	}

	// Editar o crear 
	public function edit($id = null)
	{
	
		$this->page_title = 'Editar ' . $this->tabla;
		$this->view = 'edit_' . $this->tabla;

		if (isset($_GET["id"])) {
			$id = $_GET["id"];
			$data = $this->tablaObj->getTablaById($id);
		} else {
			$this->page_title = 'Crear ' . $this->tabla;
			$data = [];
		}

		return [
			"data" => $data,
			"dataRel1" => $this->tablaObj1->getTabla()
		];
	}

	// Iniciar sesion
	public function login()
	{
		$this->page_title = 'Ingresar ' . $this->tabla;
		$this->view = 'login';
		$data = $this->tablaObj->login($_POST);

		if (isset($data["password"]) && password_verify($_POST["password"], $data['password'])) {
			return $data;
		}
		return false;
	}

	// Guardar (crear o actualizar)
	public function save()
	{
		$this->view = 'edit_' . $this->tabla;
		$this->page_title = 'Editar ' . $this->tabla;

		$id = $this->tablaObj->save($_POST);
		$result = $this->tablaObj->getTablaById($id);
		$_GET["response"] = true;

		return [
			"data" => $result,
			"dataRel1" => $this->tablaObj1->getTabla()
		];
	}

	// Confirmar eliminacion
	public function confirmDelete()
	{
		$this->page_title = 'Eliminar ' . $this->tabla;
		$this->view = 'confirm_delete';
		return $this->tablaObj->getTablaById($_GET["id"]);
	}

	// Eliminar
	public function delete()
	{
		$this->page_title = 'Listado de ' . $this->tabla;
		$this->view = 'delete';
		return $this->tablaObj->deleteTablaById($_POST["id"]);
	}

	// Campos
	public function getCampos()
	{
		return $this->tablaObj->getCampos();
	}

	// Tabla relacionada: Roles
	public function getTablaRel1()
	{
		return $this->tablaObj1->getTabla();
	}
}