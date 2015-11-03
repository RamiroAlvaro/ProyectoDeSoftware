<?php

require_once 'models/alimento.php';
require_once 'models/detalle.php';

//require_once 'controllers/Validaciones.php';
class AlimentoController extends Controller {

    public function index() {
        $this->redireccionar('index');
    }

    public function validarCampos(&$alimento) {
        $alimento->setCodigo($this->test_input($alimento->getCodigo()));
        $alimento->setDescripcion($this->test_input($alimento->getDescripcion()));

        if ($this->esVacio($alimento->getCodigo())) {
            $alimento->setError('codigo', 'Por favor complete el campo código');
        }
        if (strlen($alimento->getCodigo()) > 30) {
            $alimento->setError('codigo', 'El código no puede tener más de 30 caracteres');
        }
        if ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $alimento->getCodigo())) == 1) {
            $alimento->setError('codigo', 'El codigo sólo puede contener caracteres alfanuméricos');
        }

        if ($this->esVacio($alimento->getDescripcion())) {
            $alimento->setError('descripcion', 'Por favor complete el campo descripción');
        }
        if (strlen($alimento->getDescripcion()) > 100) {
            $alimento->setError('descripcion', 'La descripción no puede tener más de 100 caracteres');
        }
        if ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $alimento->getDescripcion())) == 1) {
            $alimento->setError('descripcion', 'La descripción sólo puede contener caracteres alfanuméricos');
        }
    }

    public function alta() {
        Session::tienePermiso('alta');
        $this->view->setEncabezado("Nuevo alimento");
        $this->view->setTitulo("Alimento");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $alimento = Alimento::nuevo($_POST['params']['alimento']);
            $this->validarCampos($alimento);
            if (count($alimento->getErrores()) == 0) {
                $repetido = Alimento::buscarPor('codigo', $alimento->getCodigo());
                if ((sizeof($repetido) == 0)) {
                    if (!$alimento->guardar()) {
                        $this->view->setError("no puedo guardarse el alimento.");
                    }
                } else {
                    $this->view->setError("el codigo ya existe");
                }
            } else {
                $this->view->setError("Modifique los campos indicados.");
            }
            if ($this->view->getError() != null) {
                $this->view->renderizar("formulario", array("alimento" => $alimento,
                    "accion" => "alta",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            } else {
                $this->redireccionar('alimento/listado/&m=a');
            }
        } else {
            $this->view->renderizar("formulario", array("accion" => "alta",
                "token" => $this->token,
                "token_id" => $this->token_id));
        }
        
    }

    public function listado($args = null) {
        /* Obtiene todos los alimentos */
        Session::tienePermiso('listado');
        $this->view->setEncabezado("Listado alimentos");
        $this->view->setTitulo("Alimento");
        //mensaje abm exitosa
        $msj = '';
        $this->cartelito();
        //fin mensaje abm exitosa
        $alimentosListado = Alimento::getStockOfall();
        $this->view->renderizar("listado", array("alimentos" => $alimentosListado,
            "mensaje" => $msj,
             "token" => $this->token,
             "token_id" => $this->token_id));
    }

    public function modificar($id) {
        Session::tienePermiso('modificar');
        $this->view->setEncabezado("Modificar alimento");
        $this->view->setTitulo("Alimento");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $alimentoModif = Alimento::nuevo($_POST['params']['alimento']);
            $this->validarCampos($alimentoModif);
            $modif = $_POST['params']['alimento'];
            $alimento = Alimento::getById($id);


            if (count($alimentoModif->getErrores()) == 0) {
                $repetido = Alimento::buscarPor('codigo', $modif['codigo']);
                if ((sizeof($repetido) == 0) || ($repetido[0]['id'] == $id)) {
                    $alimento->setCodigo($modif['codigo']);
                    $alimento->setDescripcion($modif['descripcion']);
                    if (!$alimento->actualizar()) {
                        $this->view->setError("no puedo actualizarse el alimento.");
                    }
                } else {
                    $this->view->setError("el codigo ya existe");
                }
                if ($this->view->getError() != null) {
                    $this->view->renderizar("formulario", array("alimento" => $alimento,
                        "accion" => "modificar",
                        "token" => $this->token,
                "token_id" => $this->token_id));
                } else {
                    $this->redireccionar('alimento/listado/&m=c');
                }
            } else {
                $this->view->setError("Modifique los campos indicados.");
                $alimentoModif->setId($id);
                $this->view->renderizar("formulario", array("alimento" => $alimentoModif,
                    "accion" => "modificar",
                    "token" => $this->token,
                "token_id" => $this->token_id));
            }
        } else {
            $alimento = Alimento::getById($id);
            if ($alimento->getId() != null) {
                $this->view->renderizar("formulario", array("alimento" => $alimento,
                    "accion" => "modificar",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            } else {
                $this->redireccionar('alimento/listado&m=c');
            }
        }
    }

    public function eliminar($id = null) {
        Session::tienePermiso('eliminar');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        $alimento = Alimento::getById($id);
        if ($alimento->getId() != null) {
            if ($alimento->borrar()) {
                $this->redireccionar('alimento/listado&m=b');
            } else {
                $this->view->setError("No se pudo eliminar el alimento");
            }
        } else {
            $this->redireccionar('alimento/listado');
        }
    }

    public function detalle($codigo) {
        Session::tienePermiso('detalle');
        $this->view->setEncabezado("Detalle de alimento");
        $donaciones = Detalle::getDetalleOf($codigo);
        if ($donaciones) {
            $this->view->renderizar("detalle", array("donaciones" => $donaciones,
                    "token" => $this->token,
                    "token_id" => $this->token_id));
        } else {
            $this->redireccionar('alimento/listado');
        }
    }
    
        public function eliminarDetalle($id = null) {
        Session::acceso('eliminar_detalle');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        $alimento = Detalle::getById($id);
        if ($alimento->getId() != null) {
            if ($alimento->borrar()) {
                $this->redireccionar('alimento/listado&m=b');
            } else {
                $this->view->setError("No se pudo eliminar el alimento");
            }
        } else {
            $this->redireccionar('alimento/listado');
        }
    }

}

?>