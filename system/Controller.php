<?php

include_once SYS_PATH . 'DataBase.php';

abstract class Controller {

    protected $view;
    protected $db;
    protected $csrf;
    protected $token;
    protected $token_id;

    function __construct() {
        $this->db = new DataBase();
        $this->view = new View(new Request);
        if (Session::get('autenticado')) {
            // Genera un identificador y lo valida
            $this->csrf = new Csrf();
            $this->token_id = $this->csrf->get_token_id();
            $this->token = $this->csrf->get_token($this->token_id);
        }
    }

    protected function actualizar_csrf(){
           $this->csrf->update($this->token_id);
           $this->token_id = $this->csrf->get_token_id();
           $this->token = $this->csrf->get_token($this->token_id);
    }

        abstract public function index();
    /* verifica que sea un entero */

    protected function getInt($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
            return $_POST[$clave];
        }

        return 0;
    }

    /* redirecciona a la ruta indicada o al index */

    protected function redireccionar($ruta = false) {
        if ($ruta) {
            header('location:' . BASE_URL . $ruta);
            exit;
        } else {
            header('location:' . BASE_URL);
            exit;
        }
    }

    /* asegura que se trate de un entero */

    protected function filtrarInt($int) {
        $int = (int) $int;

        if (is_int($int)) {
            return $int;
        } else {
            return 0;
        }
    }

    /* obtiene el valor de una clave de la variable $_POST */

    protected function getPostParam($clave) {
        if (isset($_POST[$clave])) {
            return $_POST[$clave];
        }
    }

    protected function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    protected function esVacio($elemento) {
        if ($elemento == '') {
            return true;
        }
        return false;
    }

    protected function check_csrf($method) {
        if (!$this->csrf->check_valid($method)) {
            header('location:' . BASE_URL . 'error/access/409');
            exit;
        }
    }

    protected function cartelito() {
        if (isset($_GET['m'])) {
            $msj = filter_input(INPUT_GET, 'm');
            if ($msj == 'a') {
                $this->view->setMensaje("Alta exitosa");
            } elseif ($msj == 'b') {
                $this->view->setMensaje("Baja exitosa");
            } elseif ($msj == 'c') {
                $this->view->setMensaje("Los cambios se guardaron con éxito");
            }
        }
    }

}

?>