<?php
// Clase Permisos para los roles
class Permisos {

    //Funcion es Administrador
    public static function esAdmin() : bool {
        return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1;
    }

    //Funcion es Editor
    public static function esEditor() : bool {
        return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 9;
    }

    //Funcion es Usuario
    public static function esUsuario() : bool {
        return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 10;
    }

    //Funcion para que deje editar a los que tienen el permiso (Administrador o Editor)
    public static function puedeEditar() : bool {
        return self::esAdmin() || self::esEditor();
    }

    //Funcion para que deje eliminar (Administrador)
    public static function puedeEliminar() : bool {
        return self::esAdmin();
    }

    //Funcion para que deje ver (Administrador, Editor o Usuario)
    public static function puedeLeer() : bool {
        return self::esAdmin() || self::esEditor() || self::esUsuario();
    }
}