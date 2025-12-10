<?php
//Incluye el modelo Turno
require_once 'model/turno.php';

//Incluye el modelo Paciente
require_once 'model/paciente.php';

class TurnoController
{

    // Atributos
    public $page_title;
    public $view;
    public $tablaObj;
    //Nombre de la tabla en la base de datos
    private $tabla = "turnos";

    // Constructor
    public function __construct()
    {
        $this->view = 'listar';
        $this->page_title = '';
        $this->tablaObj = new Turno();
    }

    // Listar turnos
    public function list()
    {
        //Titulo para la vista de la pagina
        $this->page_title = 'Listado de ' . $this->tabla;

        //Obtiene los turnos desde el modelo
        $turnos = $this->tablaObj->getTabla();

        //Retorna los turnos
        return ["data" => $turnos];
    }

    // Retorna los campos para la vista (necesario para listar.php)
    public function getCampos()
    {
        return [
            "id" => "ID",
            "paciente_id" => "Paciente",
            "fecha_turno" => "Fecha Turno",
            "hora_turno" => "Hora Turno",
            "obra_social" => "Obra Social",
            "observaciones" => "Observaciones"
        ];
    }

    // Crear o editar turno
    public function edit($id = null)
    {
        //Titulo para la vista de la pagina
        $this->view = 'edit_turnos';
        $this->page_title = $id ? 'Editar turno' : 'Crear turno';

        // Datos del turno (si existe)
        $turnoData = $id ? $this->tablaObj->getTablaById($id) : [];

        // Traer todos los pacientes para el select
        require_once 'model/paciente.php';
        $pacienteModel = new Paciente();
        $pacientes = $pacienteModel->getTabla();

        // Retornar los datos del turno y la lista de pacientes
        return [
            'data' => $turnoData,
            'pacientes' => $pacientes
        ];
    }

    // Guardar turno
    public function save()
    {
        //Verifica si el foormulario viene por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Llama al metodo save() del modelo (tablaObj) enviando los datos del formulario

            $resultado = $this->tablaObj->save($_POST);

            // Si el modelo no devolvio true, significa que hubo un error
            if ($resultado !== true) {

                //Indica la vista para mostrar el error
                $this->view = "error_turno";

                return ["error" => $resultado["error"]];
            }

            // Si no hubo error, redirecciona a la vista de listado
            header("Location: index.php?controller=turno&action=list");
            exit;
        }
    }
    // Muestra la confirmación de eliminación
    public function confirmDelete()
    {
        // Verificar si se recibio el ID por GET
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            echo "Error: ID no recibido";
            exit;
        }

        // Vista para confirmar la eliminación
        $this->view = 'confirm_delete_turnos';
        $this->page_title = 'Eliminar turno';

        // Obtener los datos del turno
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
        // Verificar si se recibio el ID por POST 
        if (!isset($_POST["id"]) || empty($_POST["id"])) {
            echo "Error: ID no recibido";
            exit;
        }

        //Elimina el turno que llega por POST
        $result = $this->tablaObj->deleteTablaById($_POST["id"]);

        //Redirige con respuesta
        if ($result) {
            //True
            header("Location: index.php?controller=turno&action=list&response=true");
        } else {
            //False
            header("Location: index.php?controller=turno&action=list&response=false");
        }
    }
}
