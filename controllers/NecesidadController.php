<?php

require_once 'models/necesidad_entidad.php';

//require_once 'controllers/Validaciones.php';
class NecesidadController extends Controller {

    public function index() {
        $this->redireccionar('index');
    }
    
     public function validarCampos(&$necesidad) {
        $necesidad->setDescripcion($this->test_input($necesidad->getDescripcion()));
       

        if ($this->esVacio($necesidad->getDescripcion())) {
            $necesidad->setError('descripcion', 'Por favor complete el campo descripcion');
        } elseif (strlen($necesidad->getDescripcion()) > 15) {
            $necesidad->setError('descripcion', 'La descripcion no puede tener más de 15 caracteres');
        } elseif ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $necesidad->getDescripcion())) == 1) {
            $necesidad->setError('descripcion', 'La descripcion sólo puede contener caracteres alfanuméricos');
        }
     }
    public function alta() {
        Session::tienePermiso('alta');
        $this->view->setEncabezado("Nueva necesidad");
        $this->view->setTitulo("Necesidad");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $necesidad = NecesidadEntidad::nuevo($_POST['params']['necesidad']);
            $this->validarCampos($necesidad);
            if (count($necesidad->getErrores()) == 0) {
                $repetido = NecesidadEntidad::buscarPor('descripcion', $necesidad->getDescripcion());
                if ((sizeof($repetido) == 0)) {
                    if (!$necesidad->guardar()) {
                        $this->view->setError("no puedo guardarse el necesidad.");
                    }
                } else {
                    $this->view->setError("La descripcion ya existe");
                }
            } else {
                $this->view->setError("Modifique los campos indicados.");
            }
            if ($this->view->getError() != null) {
                $this->view->renderizar("formulario", array("necesidad" => $necesidad,
                    "accion" => "alta",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            } else {
                $this->redireccionar('necesidad/listado/&m=a');
            }
        } else {
            $this->view->renderizar("formulario", array("accion" => "alta", "encabezado" => "Nuevo necesidad",
                                                        "token" => $this->token,
                                                        "token_id" => $this->token_id));
        }
    }

    public function listado($args = null) {
        /* Obtiene todos los necesidades */
        Session::tienePermiso('listado');
        // seteo de mensaje de exito
        if (isset($_GET['m'])) {
            $msj = filter_input(INPUT_GET, 'm');
            if ($msj == 'a') {
                $this->view->setMensaje("Alta exitosa");
            }
            elseif ($msj == 'b') {
                $this->view->setMensaje("Baja exitosa");
            }
            elseif ($msj == 'c') {
                $this->view->setMensaje("Los cambios se guardaron con éxito"); 
            }            
        }
        //fin seteo de mensaje de exito
        $this->view->setEncabezado("Listado necesidades");
        $this->view->setTitulo("Necesidad");
        $necesidades = NecesidadEntidad::obtenerTodos();
        $this->view->renderizar("listado", array("necesidades" => $necesidades,
                                                 "token" => $this->token,
                                                 "token_id" => $this->token_id));
    }

    public function modificar($id) {
        Session::tienePermiso('modificar');
        $this->view->setEncabezado("Modificar necesidad");
        $this->view->setTitulo("Necesidad");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $necesidadModif = NecesidadEntidad::nuevo($_POST['params']['necesidad']);
            $this->validarCampos($necesidadModif);
            $modif = $_POST['params']['necesidad'];
            $necesidad = NecesidadEntidad::getById($id);
            if (count($necesidadModif->getErrores()) == 0) {
                $repetido = NecesidadEntidad::buscarPor('descripcion', $modif['descripcion']);
                if ((sizeof($repetido) == 0) || ($repetido[0]['id'] == $id)) {                    
                    $necesidad->setDescripcion($modif['descripcion']);
                    if (!$necesidad->actualizar()) {
                        $this->view->setError("no puedo actualizarse la necesidad.");
                    }
                } else {
                    $this->view->setError("La descripcion ya existe");
                }
                if ($this->view->getError() != null) {
                    $this->view->renderizar("formulario", array("necesidad" => $necesidad,
                                                                "accion" => "modificar",
                                                                "encabezado" => "Modificar necesidad",
                                                                "token" => $this->token,
                                                                "token_id" => $this->token_id));
                } else {
                    $this->redireccionar('necesidad/listado/&m=c');
                }
            }
             else {
                $this->view->setError("Modifique los campos indicados.");
                $necesidadModif->setId($id);
                $this->view->renderizar("formulario", array("necesidad" => $necesidadModif,
                    "accion" => "modificar",
                    "encabezado" => "Modificar necesidad",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            }
            
        } else {
            $necesidad = NecesidadEntidad::getById($id);
            if ($necesidad->getId() != null) {
                $this->view->renderizar("formulario", array("necesidad" => $necesidad,
                                                            "accion" => "modificar",
                                                            "encabezado" => "Modificar necesidad",
                                                            "token" => $this->token,
                                                            "token_id" => $this->token_id));
            } else {
                $this->redireccionar('necesidad/listado');
            }
        }
    }

    public function eliminar($id = null) {
        Session::tienePermiso('eliminar');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        $necesidad = NecesidadEntidad::getById($id);
        if ($necesidad->getId() != null) {
            if($necesidad->borrar()){
                $this->redireccionar('necesidad/listado/&m=b');
            }else{
                $this->view->setError("No se pudo eliminar la necesidad");
            }
        } else {
            $this->redireccionar('necesidad/listado');
        }
    }

    public function detalle($id) {
        Session::tienePermiso('detalle');
        $necesidad = NecesidadEntidad::getById($id);
        if ($necesidad->getId() != null) {
            $this->view->renderizar("detalle", array("necesidad" => $necesidad));
        } else {
            $this->redireccionar('necesidad/listado');
        }
    }

}

?>

