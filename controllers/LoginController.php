<?php

require_once 'models/login.php';
require_once 'models/permiso.php';

class LoginController extends Controller {

    private $_login;

    public function __construct() {
        parent::__construct();
        $this->_login = new Login();
    }

    public function index() {
        $titulo = 'Iniciar Sesion';
        /* verificar que se intenta loguear correctamente por el atributo enviar del formulario */
        if ($this->getInt('enviar') == 1) {
            $datos = $_POST;
            /* verif de campos vacios */
            if (!$datos['username']) {
                $this->view->setError('Debe introducir su nombre de usuario');
                $this->view->setControlador("index");
                $this->view->renderizar("index");
                exit;
            }

            if (!$datos['pass']) {
                $this->view->setError('Debe introducir su password');
                $this->view->setControlador("index");
                $this->view->renderizar("index");
                exit;
            }
            /* obtencion del usuario */
            $row = $this->_login->getUsuario($datos['username'], $datos['pass']);

            if (!$row) {
                $this->view->setError('Usuario y/o password incorrectos');
                $this->view->setControlador("index");
                $this->view->renderizar("index");
                exit;
            }

            if ($row['baja'] == 1) {
                $this->view->setError('Error este usuario no esta habilitado');
                $this->view->setControlador("index");
                $this->view->renderizar("index");
                exit;
            }
            Session::set('autenticado', true);
            Session::set('username', $row['username']);
            Session::set('id_usuario', $row['id']);
            Session::set('permisos', Permiso::obtenerPermisosDe($row['id']));
            Session::set('grupos', Permiso::obtenergrupos());
            Session::set('rol', Permiso::obtenerRol($row['id']));
            Session::set('tiempo', time());
            
            $this->redireccionar('index/admin');
        }

        $this->redireccionar();
    }

    public function cerrar() {
        Session::destroy();
        $this->redireccionar('login/mostrar');
    }

}

?>
