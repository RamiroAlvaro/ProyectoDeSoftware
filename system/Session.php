<?php

class Session {
    
    public static function init() {
        session_start();
     }

    public static function destroy($clave = false) {
        if ($clave) {
            if (is_array($clave)) {
                for ($i = 0; $i < count($clave); $i++) {
                    if (isset($_SESSION[$clave[$i]])) {
                        unset($_SESSION[$clave[$i]]);
                    }
                }
            } else {
                if (isset($_SESSION[$clave])) {
                    unset($_SESSION[$clave]);
                }
            }
        } else {
            session_destroy();
        }
    }

    /* setea en $_SESSION una nueva clave con un valor */

    public static function set($clave, $valor) {
        if (!empty($clave))
            $_SESSION[$clave] = $valor;
    }

    /* obtiene el valor de $_SESSION en la clave indicada */

    public static function get($clave) {
        if (isset($_SESSION[$clave]))
            return $_SESSION[$clave];
    }
    
    /* Verifica que el usuario tenga el permiso especifico para la accion deseada (complejo pero flexible) */

    public static function tienePermiso($accion) {

        if (!Session::get('autenticado')) {
            header('location:' . BASE_URL . 'error/access/401');
            exit;
        }
        
        if (!in_array($accion, Session::get('permisos'))) {
            header('location:' . BASE_URL . 'error/access/401');
            exit;
        }

    }
    
        /* Verifica que el usuario tenga el permiso especifico para la accion deseada (complejo pero flexible) */

    public static function tienePermisoView($accion) {

        if (!Session::get('autenticado')) {
            return false;
        }
        
        if (!in_array($accion, Session::get('permisos'))) {
            return false;
        }
        
        return true;
    }

    /* verifica acceso dado un rol (es mas simple pero menos flexible) */

    public static function acceso($rol) {
        if (!Session::get('autenticado')) {
            header('location:' . BASE_URL . 'error/access/401');
            exit;
        }

        if (Session::getLevel($rol) < Session::getLevel(Session::get('rol'))) {
            header('location:' . BASE_URL . 'error/access/401');
            exit;
        }
               
    }

    /* verifica acceso dado un rol para la vista (es mas simple pero menos flexible) */

    public static function accesoView($level) {
        if (!Session::get('autenticado')) {
            return false;
        }

        if (Session::getLevel($level) < Session::getLevel(Session::get('rol'))) {
            return false;
        }
        
        return true;
    }
    
    public static function accesoViewDos($level) {
        if (!Session::get('autenticado')) {
            return false;
        }

        if ($level == Session::get('rol')) {
            return true;
        }
        
        return false;
    }

    /* obtiene en nivel de acceso del un rol (el nivel es un intiger) */

    public static function getLevel($rol) {
        if (!array_key_exists($rol, Session::get('grupos'))) {
            throw new Exception('Error de acceso');
        } else {
            return Session::get('grupos')[$rol];
        }
    }

    /* verifica acceso exclusivo dado un rol (es mas simple pero menos flexible) */

    public static function accesoEstricto(array $rol, $noAdmin = false) {
        if (!Session::get('autenticado')) {
            header('location:' . BASE_URL . 'error/access/401');
            exit;
        }

        if ($noAdmin == false) {
            if (Session::get('rol') == 'admin') {
                return;
            }
        }

        if (count($rol)) {
            if (in_array(Session::get('rol'), $rol)) {
                return;
            }
        }

        header('location:' . BASE_URL . 'error/access/401');
    }

    /* verifica acceso exclusivo dado un rol para la vista (es mas simple pero menos flexible) */

    public static function accesoViewEstricto(array $level, $noAdmin = false) {
        if (!Session::get('autenticado')) {
            return false;
        }

        if ($noAdmin == false) {
            if (Session::get('rol') == 'admin') {
                return true;
            }
        }

        if (count($level)) {
            if (in_array(Session::get('rol'), $level)) {
                return true;
            }
        }

        return false;
    }

}

?>