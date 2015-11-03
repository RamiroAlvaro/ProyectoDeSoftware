<?php

include_once MOD_PATH . 'alimento.php';
include_once MOD_PATH . 'detalle.php';
include_once MOD_PATH . 'donante.php';
include_once MOD_PATH . 'donacion.php';

class DonacionController extends Controller {

      public function index() {
        $this->redireccionar('index');
    }
    
     public function validar($detalle) {
         
   }

    public function alta() {
        Session::tienePermiso('alta');
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $alimentos = Alimento::obtenerTodos();
            $donantes = Donante::obtenerTodos();
            $params=$_POST['params'];
            $detalle = Detalle::nuevo($params['donacion']);
            $this->validar($detalle);            
            if (count($detalle->getErrores()) == 0) {
                if (!$detalle->guardar()) {
                    $this->view->setError("no puedo guardarse el detalle.");
                } else {
                    $detalle_id = Model::lastInsertId();
                    $params['donacion']['detalle_id'] = $detalle_id;
                    $donacion = Donacion::nuevo($params['donacion']);
                    if (!$donacion->guardar()) {
                        $this->view->setError("se guardo el detalle pero no la relacion con quien hizo la donación.");
                    } else {
                        $this->view->setMensaje("Toso se guardo correctamente.");
                    }
                }
            } else {
                $this->view->setMensaje("Modifique los campos indicados.");
            }
            if ($this->view->getError() != null) {
                if (isset($donacion)) {
                    $this->view->renderizar("formulario", array(
                        "alimentos" => $alimentos,
                        "donantes" => $donantes,
                        "detalle" => $detalle,
                        "donacion" => $donacion,
                        "accion" => "alta",
                        "token" => $this->token,
                        "token_id" => $this->token_id ));
                } else {
                    $this->view->renderizar("formulario", array(
                        "alimentos" => $alimentos,
                        "donantes" => $donantes,
                        "detalle" => $params['donacion'],
                        "accion" => "alta",
                        "token" => $this->token,
                        "token_id" => $this->token_id));
                }
            } else {
                $this->redireccionar('donacion/alta');
            }
        } else {
            $alimentos = Alimento::obtenerTodos();
            $donantes = Donante::obtenerTodos();
            $this->view->setEncabezado("Nueva donacion");
            $this->view->renderizar("formulario", array("accion" => "alta",
                "alimentos" => $alimentos,
                "donantes" => $donantes,
                "token" => $this->token,
                "token_id" => $this->token_id));
        }
    }

    public function listado($args = null) {
        /* Obtiene todos los alimentos */
        Session::tienePermiso('listado');
        $donaciones = Donacion::obtenerTodos();
        $this->view->renderizar("listado", array("donaciones" => $donaciones,
                                                 "token" => $this->token,
                                                 "token_id" => $this->token_id ));
    }

    public function modificar($id) {
        Session::tienePermiso('modificar');
        if (isset($_POST['aux'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $modif = $_POST['params']['alimento'];
            $alimento = Alimento::getById($id);
            if (count($alimento->getErrores()) == 0) {
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
                        "encabezado" => "Modificar alimento",
                        "token" => $this->token,
                        "token_id" => $this->token_id));
                } else {
                    $this->redireccionar('alimento/listado');
                }
            }
        } else {
            $alimento = Alimento::getById($id);
            if ($alimento->getId() != null) {
                $this->view->renderizar("formulario", array("alimento" => $alimento,
                    "accion" => "modificar",
                    "encabezado" => "Modificar alimento",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            } else {
                $this->redireccionar('alimento/listado');
            }
        }
    }

    public function eliminar($id = null) {
        Session::tienePermiso('eliminar');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        if ( Detalle::exist($id)) {
            $bool = Detalle::borrar($id);
            if ($bool) {
                    $this->redireccionar('alimento/listado');
            } else {
                $this->view->setError("No se pudo eliminar el alimento");
            }
        } else {
            $this->redireccionar('alimento/listado');
        }
    }


}

?>