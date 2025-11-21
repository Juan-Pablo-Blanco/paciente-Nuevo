<?php

//Incluye el modelo Rol
require_once 'model/rol.php';

class RolController{
	
	//Atributos
	public $page_title;
	public $view;
	public $tablaObj;
	//Nombre de la tabla en la base de datos
	private $tabla="roles";

	//Constructor
	public function __construct() {
		$this->view = 'listar';
		$this->page_title = '';
		$this->tablaObj = new Rol();
	}

	// Lista 
	public function list(){
		//Titulo para la vista de la pagina
		$this->page_title = 'Listado de '. $this->tabla;

		//Obtiene los pacientes desde el modelo
		return $this->tablaObj->getTabla();
	}

	// Crear o editar
	public function edit($id=null){
		//Titulo segun si Edita o Crea
		$this->page_title = 'Editar '. $this->tabla;
		$this->view = 'edit_'. $this->tabla;

		//Si recibe el id edita
		if (isset($_GET["id"])) {
			$id = $_GET["id"];
		}

		//Si no hay id, se crea
		else {
			$this->page_title = 'Crear ' . $this->tabla;
		}

		//Retorna los datos a la vista
		return $this->tablaObj->getTablaById($id);
	}

	/* Create or update */
	public function save(){
		//Titulo para la edicion
		$this->view = 'edit_'. $this->tabla;
		$this->page_title = 'Editar '. $this->tabla;

		//Guarda la informcion
		$id = $this->tablaObj->save($_POST);

		//Obiene nuevamente el registro guardado para mostrarlo actualizado
		$result = $this->tablaObj->getTablaById($id);

		//Responde que fue guardado 
		$_GET["response"] = true;
		return $result;
	}

	//Confirmar eliminación
	public function confirmDelete(){
		//Titulo de la vista
		$this->page_title = 'Eliminar '. $this->tabla;
		$this->view = 'confirm_delete_pacientes';
		
		//Retorna los datos a la vista
		return $this->tablaObj->getTablaById($_GET["id"]);
	}

	// Delete
	public function delete(){
		//Titulo de la vista
		$this->page_title = 'Listado de '. $this->tabla;
		$this->view = 'delete';
		
		//Elimina el registro que llega por POST
		return $this->tablaObj->deleteTablaById($_POST["id"]);
	}

	// Campos con su descripción 
	public function getCampos(){
	
		//Retorna los campos
		return $this->tablaObj->getCampos();
	}
}

?>