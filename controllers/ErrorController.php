<?php

class ErrorController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view->setTitulo('Error');
        $this->view->setMensaje($this->_getError());
        $this->view->renderizar("index", array());
    }

    public function access($codigo) {
        $this->view->setTitulo('Error');
        $this->view->setMensaje($this->_getError($codigo));
        $this->view->renderizar("access", array());
    }

    private function _getError($codigo = false) {
        if ($codigo) {
            $codigo = $this->filtrarInt($codigo);
            if (is_int($codigo))
                $codigo = $codigo;
        }
        else {
            $codigo = 'default';
        }

        $error['default'] = 'Ha ocurrido un error y la página no puede mostrarse';
        $error['401'] = 'Acceso restringido!';
        $error['404'] = 'Pagina no encontrara. Error: 404.';
        $error['409'] = 'Estas tratando de acceder de una manera insegura';

        if (array_key_exists($codigo, $error)) {
            return $error[$codigo];
        } else {
            return $error['default'];
        }
    }

}

?>