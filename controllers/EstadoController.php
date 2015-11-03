<?php

require_once 'models/estado_entidad.php';

//require_once 'controllers/Validaciones.php';
class EstadoController extends Controller {

    public function index() {
        $this->redireccionar('index');
    }
    
     public function validarCampos(&$estado) {
        $estado->setDescripcion($this->test_input($estado->getDescripcion()));
       

        if ($this->esVacio($estado->getDescripcion())) {
            $estado->setError('descripcion', 'Por favor complete el campo descripcion');
        } elseif (strlen($estado->getDescripcion()) > 20) {
            $estado->setError('descripcion', 'La descripcion no puede tener más de 20 caracteres');
        } elseif ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $estado->getDescripcion())) == 1) {
            $estado->setError('descripcion', 'La descripcion sólo puede contener caracteres alfanuméricos');
        }

      
    }

    public function alta() {
        Session::tienePermiso('alta');
        $this->view->setEncabezado("Nuevo estado");
        $this->view->setTitulo("Estado");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $estado = EstadoEntidad::nuevo($_POST['params']['estado']);
            $this->validarCampos($estado);
            if (count($estado->getErrores()) == 0) {
                $repetido = EstadoEntidad::buscarPor('descripcion', $estado->getDescripcion());
                if ((sizeof($repetido) == 0)) {
                    if (!$estado->guardar()) {
                        $this->view->setError("no puedo guardarse el estado.");
                    }
                } else {
                    $this->view->setError("La descripcion ya existe");
                }
            } else {
                $this->view->setError("Modifique los campos indicados.");
            }
            if ($this->view->getError() != null) {
                $this->view->renderizar("formulario", array("estado" => $estado,
                    "accion" => "alta",
                    "token" => $this->token,
                    "token_id" => $this->token_id ));
            } else {
                $this->redireccionar('estado/listado/&m=a');
            }
        } else {
            $this->view->renderizar("formulario", array("accion" => "alta", "encabezado" => "Nuevo estado",
                                                        "token" => $this->token,
                                                        "token_id" => $this->token_id));
        }
    }

    public function listado($args = null) {
        /* Obtiene todos los estados */
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
        $this->view->setEncabezado("Listar estados");
        $this->view->setTitulo("Estado");
        $estados = EstadoEntidad::obtenerTodos();
        $this->view->renderizar("listado", array("estados" => $estados,
                                                 "token" => $this->token,
                                                 "token_id" => $this->token_id ));
    }

    public function modificar($id) {
        Session::tienePermiso('modificar');
        $this->view->setEncabezado("Modificar estado");
        $this->view->setTitulo("Estado");
       if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $estadoModif = EstadoEntidad::nuevo($_POST['params']['estado']);
            $this->validarCampos($estadoModif);
            $modif = $_POST['params']['estado'];
            $estado = EstadoEntidad::getById($id);
            if (count($estadoModif->getErrores()) == 0) {
                $repetido = EstadoEntidad::buscarPor('descripcion', $modif['descripcion']);
                if ((sizeof($repetido) == 0) || ($repetido[0]['id'] == $id)) {                    
                    $estado->setDescripcion($modif['descripcion']);
                    if (!$estado->actualizar()) {
                        $this->view->setError("no puedo actualizarse el estado.");
                    }
                } else {
                    $this->view->setError("La descripcion ya existe");
                }
                if ($this->view->getError() != null) {
                    $this->view->renderizar("formulario", array("estado" => $estado,
                                                                "accion" => "modificar",
                                                                "encabezado" => "Modificar estado",
                                                                "token" => $this->token,
                                                                "token_id" => $this->token_id));
                } else {
                    $this->redireccionar('estado/listado/&m=c');
                }
            }
            else {
                $this->view->setError("Modifique los campos indicados.");
                $estadoModif->setId($id);
                $this->view->renderizar("formulario", array("estado" => $estadoModif,
                    "accion" => "modificar",
                    "encabezado" => "Modificar estado",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            }
        } else {
            $estado = EstadoEntidad::getById($id);
            if ($estado->getId() != null) {
                $this->view->renderizar("formulario", array("estado" => $estado,
                                                            "accion" => "modificar",
                                                            "encabezado" => "Modificar estado",
                                                            "token" => $this->token,
                                                            "token_id" => $this->token_id));
            } else {
                $this->redireccionar('estado/listado');
            }
        }
    }

    public function eliminar($id = null) {
        Session::tienePermiso('eliminar');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        $estado = EstadoEntidad::getById($id);
        if ($estado->getId() != null) {
            if($estado->borrar()){
                $this->redireccionar('estado/listado/&m=c');
            }else{
                $this->view->setError("No se pudo eliminar el estado");
            }
        } else {
            $this->redireccionar('estado/listado');
        }
    }

    public function detalle($id) {
        Session::tienePermiso('detalle');
        $estado = EstadoEntidad::getById($id);
        if ($estado->getId() != null) {
            $this->view->renderizar("detalle", array("estado" => $estado));
        } else {
            $this->redireccionar('estado/listado');
        }
    }

}

?>

