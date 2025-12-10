<?php

//Incluye el modelo Paciente
require_once 'model/paciente.php';

// Se crea la clase PacienteController
class PacienteController
{

    // Atributos
    public $page_title;
    public $view;
    public $tablaObj;
    // Nombre de la tabla en la base de datos
    private $tabla = "pacientes";

    // Constructor
    public function __construct()
    {
        $this->view = 'listar';
        $this->page_title = '';
        $this->tablaObj = new Paciente();
    }

    //Lista 
    public function list()
    {
        //Titulo para la vista de la pagina
        $this->page_title = 'Listado de ' . $this->tabla;

        //Obtiene los pacientes desde el modelo
        $pacientes = $this->tablaObj->getTabla();

        //Si no hay pacientes ,muestra un warning 
        if (empty($pacientes)) {
            echo "<div class='alert alert-warning m-2'>⚠️ No hay pacientes cargados en la base de datos.</div>";
        }

        //Retorna los pacientes
        return ["data" => $pacientes];
    }

    // Obtener campos
    public function getCampos()
    {
        //Arreglo que definen los nombres de cada columna
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

        $this->view = 'edit_pacientes'; // Vista del formulario 

        //Titulo segun si Edita o Crea
        $this->page_title = $id ? 'Editar paciente' : 'Crear paciente';

        //Si recibe el id edita, sino crea
        $pacienteData = $id ? $this->tablaObj->getTablaById($id) : [];

        //Retorna los datos a la vista
        return ["data" => $pacienteData];
    }

    // Guardar (crear o actualizar)
    public function save()
    {
        //Solo funciona si llega un POST desde el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // hace el guardado o el Update
            $id = $this->tablaObj->save($_POST);

            // Redirecciona a la vista de listado luego de guardar
            header("Location: index.php?controller=paciente&action=list");
            exit();
        }
    }

    // Confirmar eliminación
    public function confirmDelete()
    {
        // Verificar si se recibio el ID por GET
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            echo "Error: ID no recibido";
            exit;
        }

        $id = (int) $_GET["id"];

        // Vista de confirmación
        $this->view = 'confirm_delete_pacientes';
        $this->page_title = 'Eliminar paciente';

        // Obtener los datos del paciente
        $dataToView["data"] = $this->tablaObj->getTablaById($id);
        $dataToView["campos"] = $this->getCampos();

        // Verificar si tiene turnos asociados
        $relaciones = [];
        if ($this->tablaObj->tieneTurnos($id)) {
            $relaciones[] = "turnos asociados";
        }

        // Pasar las relaciones a la vista
        $dataToView["relaciones"] = $relaciones;

        return $dataToView;
    }

    // Eliminar
    public function delete()
    {
        // Verificar si se recibio el ID por POST
        if (!isset($_POST["id"]) || empty($_POST["id"])) {
            echo "Error: ID no recibido";
            exit;
        }

        $id = (int) $_POST["id"];

        // Doble validación: impedir eliminación si tiene turnos
        if ($this->tablaObj->tieneTurnos($id)) {
            header("Location: index.php?controller=paciente&action=confirmDelete&id=$id");
            exit;
        }

        //Llamar al modelo para eliminar el paciente
        $result = $this->tablaObj->deleteTablaById($id);

        //Redirige con respuesta (True / False)
        header("Location: index.php?controller=paciente&action=list&response=" . ($result ? "true" : "false"));
        exit;
    }

    //verifica si el paciente tiene turnos asociados
    public function pacienteTieneTurnos($id)

    {
        return $this->tablaObj->tieneTurnos($id);
    }
}
