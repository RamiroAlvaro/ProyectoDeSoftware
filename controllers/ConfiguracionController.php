<?php

require_once 'models/configuracion.php';

//require_once 'controllers/Validaciones.php';
class ConfiguracionController extends Controller {

    public function index() {
        $this->redireccionar('index');
    }

    public function validarCampos(&$configuracion) {
        $configuracion->setClave($this->test_input($configuracion->getClave()));
        $configuracion->setValor($this->test_input($configuracion->getValor()));

        if ($this->esVacio($configuracion->getClave())) {
            $configuracion->setError('clave', 'Por favor complete el campo clave');
        }
        if (strlen($configuracion->getClave()) > 50) {
            $configuracion->setError('clave', 'La clave no puede tener más de 50 caracteres');
        }
        if ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $configuracion->getClave())) == 1) {
            $configuracion->setError('clave', 'La clave sólo puede contener caracteres alfanuméricos');
        }
        if (($configuracion->getClave()) == "vencimiento_stock") {
            if ($this->esVacio($configuracion->getValor())) {
                $configuracion->setError('valor', 'Por favor complete el campo valor');
            }
            if (strlen($configuracion->getValor()) > 2) {
                $configuracion->setError('valor', 'El vencimiento no debe superar los 99 dias');
            }
            if ((preg_match('/^[0-9]*$/', $configuracion->getValor())) == 0) {
                $configuracion->setError('valor', 'El valor sólo puede contener caracteres numericos');
            }
        }
        
          if (($configuracion->getClave()) == "mapa_longitud") {
            if ($this->esVacio($configuracion->getValor())) {
                $configuracion->setError('valor', 'Por favor complete el campo valor');
            }
            if (strlen($configuracion->getValor()) > 150) {
                $configuracion->setError('valor', 'El valor no puede tener más de 150 caracteres');
            }
            if ((preg_match('/([0-9.-]+).+?([0-9.-]+)/', $configuracion->getValor())) == 0) {
                $configuracion->setError('valor', 'El valor sólo puede contener caracteres numericos');
            }
            if (( $configuracion->getValor() < -180 ) || (  180 < $configuracion->getValor() ) ){
                $configuracion->setError('valor', 'El valor debe estar un rango entre -180 y 180');
            }
        }
        
          if (($configuracion->getClave()) == "mapa_latitud") {
            if ($this->esVacio($configuracion->getValor())) {
                $configuracion->setError('valor', 'Por favor complete el campo valor');
            }
            if (strlen($configuracion->getValor()) > 150) {
                $configuracion->setError('valor', 'El valor no puede tener más de 150 caracteres');
            }
            if ((preg_match('/([0-9.-]+).+?([0-9.-]+)/', $configuracion->getValor())) == 0) {
                $configuracion->setError('valor', 'El valor sólo puede contener caracteres numericos');
            }
            if (( $configuracion->getValor() < -90 ) || (  90 < $configuracion->getValor() ) ){
                $configuracion->setError('valor', 'El valor debe estar un rango entre -90 y 90');
            }
        }
        
        if ((($configuracion->getClave()) == "linkedin_api_key") || (($configuracion->getClave()) == "linkedin_secret_key") ||(($configuracion->getClave()) == "linkedin_oauth_user_token") || (($configuracion->getClave()) == "linkedin_oauth_user_secret") ){
        if ($this->esVacio($configuracion->getValor())) {
            $configuracion->setError('valor', 'Por favor complete el campo valor');
        }
        if (strlen($configuracion->getValor()) > 150) {
            $configuracion->setError('valor', 'El valor no puede tener más de 150 caracteres');
        }
        if ((preg_match('/[#$%^&*()+=\\[\]\';,.\/{}|":<>?~\\\\]/', $configuracion->getValor())) == 1) {
            $configuracion->setError('valor', 'El valor sólo puede contener caracteres alfanuméricos');
        }
        }
        }
    

    public function listado($args = null) {
        /* Obtiene todas las configuraciones */
        Session::acceso('admin');
        $this->view->setEncabezado("Listado de configuracion");
        $this->view->setTitulo("Configuracion");
        $configuracion = Configuracion::obtenerTodos();
        $this->view->setTitulo("Configuracion");
        //mensaje abm exitosa
        $msj = '';
        $this->cartelito();

        $this->view->renderizar("listado", array("configuraciones" => $configuracion,
            "mensaje" => $msj,
            "token" => $this->token,
            "token_id" => $this->token_id));
    }

    public function modificar($id) {
        Session::acceso('admin');
        $this->view->setEncabezado("Modificar configuracion");
        $this->view->setTitulo("Configuracion");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $configuracionModif = Configuracion::nuevo($_POST['params']['configuracion']);
            $this->validarCampos($configuracionModif);
            $modif = $_POST['params']['configuracion'];
            $configuracion = Configuracion::getById($id);


            if (count($configuracionModif->getErrores()) == 0) {
                $repetido = Configuracion::buscarPor('clave', $modif['clave']);
                if ((sizeof($repetido) == 0) || ($repetido[0]['id'] == $id)) {
                    $configuracion->setClave($modif['clave']);
                    $configuracion->setValor($modif['valor']);
                    if (!$configuracion->actualizar()) {
                        $this->view->setError("no puedo actualizarse la configuracion.");
                    }
                } else {
                    $this->view->setError("la clave ya existe");
                }
                if ($this->view->getError() != null) {
                    $this->view->renderizar("formulario", array("configuracion" => $configuracion,
                        "accion" => "modificar",
                        "token" => $this->token,
                        "token_id" => $this->token_id));
                } else {
                    $this->redireccionar('configuracion/listado/&m=c');
                }
            } else {
                $this->view->setError("Modifique los campos indicados.");
                $configuracionModif->setId($id);
                $this->view->renderizar("formulario", array("configuracion" => $configuracionModif,
                    "accion" => "modificar",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            }
        } else {
            $configuracion = Configuracion::getById($id);
            if ($configuracion->getId() != null) {
                $this->view->renderizar("formulario", array("configuracion" => $configuracion,
                    "accion" => "modificar",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            } else {
                $this->redireccionar('configuracion/listado&m=c');
            }
        }
    }

}

?>