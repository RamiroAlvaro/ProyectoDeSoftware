<?php

require_once 'models/servicio_prestado.php';

//require_once 'controllers/Validaciones.php';
class ServicioController extends Controller {

    public function index() {
        $this->redireccionar('index');
    }
    
     private function validarCampos(&$servicio) {
        $servicio->setDescripcion($this->test_input($servicio->getDescripcion()));
       

        if ($this->esVacio($servicio->getDescripcion())) {
            $servicio->setError('descripcion', 'Por favor complete el campo descripcion');
        } elseif (strlen($servicio->getDescripcion()) > 100) {
            $servicio->setError('descripcion', 'La descripcion no puede tener más de 100 caracteres');
        } elseif ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $servicio->getDescripcion())) == 1) {
            $servicio->setError('descripcion', 'La descripcion sólo puede contener caracteres alfanuméricos');
        }

      
    }

    public function alta() {
        Session::tienePermiso('alta');
         $this->view->setEncabezado("Nuevo servicio");
        $this->view->setTitulo("Servicio");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $servicio = ServicioPrestado::nuevo($_POST['params']['servicio']);
            $this->validarCampos($servicio);
            if (count($servicio->getErrores()) == 0) {
                $repetido = ServicioPrestado::buscarPor('descripcion', $servicio->getDescripcion());
                if ((sizeof($repetido) == 0)) {
                    if (!$servicio->guardar()) {
                        $this->view->setError("no puedo guardarse el servicio.");
                    }
                } else {
                    $this->view->setError("La descripcion ya existe");
                }
            } else {
                $this->view->setError("Modifique los campos indicados.");
            }
            if ($this->view->getError() != null) {
                $this->view->renderizar("formulario", array("servicio" => $servicio,
                    "accion" => "alta",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            } else {
                $this->redireccionar('servicio/listado/&m=a');
            }
        } else {
            $this->view->renderizar("formulario", array("accion" => "alta", "encabezado" => "Nuevo servicio",
                                                        "token" => $this->token,
                                                        "token_id" => $this->token_id ));
        }
    }

    public function listado($args = null) {
        /* Obtiene todos los servicios */
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
          $this->view->setEncabezado("Listado servicios");
        $this->view->setTitulo("Servicio");
        $servicios = ServicioPrestado::obtenerTodos();
        $this->view->renderizar("listado", array("servicios" => $servicios,
                                                 "token" => $this->token,
                                                 "token_id" => $this->token_id ));
    }

    public function modificar($id) {
        Session::tienePermiso('modificar');
          $this->view->setEncabezado("Modificar servicio");
        $this->view->setTitulo("Servicio");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $servicioModif = ServicioPrestado::nuevo($_POST['params']['servicio']);
            $this->validarCampos($servicioModif);
            $modif = $_POST['params']['servicio'];
            $servicio = ServicioPrestado::getById($id);
            if (count($servicioModif->getErrores()) == 0) {
                $repetido = ServicioPrestado::buscarPor('descripcion', $modif['descripcion']);
                if ((sizeof($repetido) == 0) || ($repetido[0]['id'] == $id)) {                    
                    $servicio->setDescripcion($modif['descripcion']);
                    if (!$servicio->actualizar()) {
                        $this->view->setError("no pudo actualizarse el servicio.");
                    }
                } else {
                    $this->view->setError("La descripcion ya existe");
                }
                if ($this->view->getError() != null) {
                    $this->view->renderizar("formulario", array("servicio" => $servicio,
                                                                "accion" => "modificar",
                                                                "encabezado" => "Modificar servicio",
                                                                "token" => $this->token,
                                                                "token_id" => $this->token_id));
                } else {
                    $this->redireccionar('servicio/listado/&m=c');
                }
            }
            else {
                $this->view->setError("Modifique los campos indicados.");
                $servicioModif->setId($id);
                $this->view->renderizar("formulario", array("servicio" => $servicioModif,
                    "accion" => "modificar",
                    "encabezado" => "Modificar servicio",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            }
        } else {
            $servicio = ServicioPrestado::getById($id);
            if ($servicio->getId() != null) {
                $this->view->renderizar("formulario", array("servicio" => $servicio,
                                                            "accion" => "modificar",
                                                            "encabezado" => "Modificar servicio",
                                                            "token" => $this->token,
                                                            "token_id" => $this->token_id));
            } else {
                $this->redireccionar('servicio/listado');
            }
        }
    }

    public function eliminar($id = null) {
        Session::tienePermiso('eliminar');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        $servicio = ServicioPrestado::getById($id);
        if ($servicio->getId() != null) {
            if($servicio->borrar()){
                $this->redireccionar('servicio/listado/&m=b');
            }else{
                $this->view->setError("No se pudo eliminar el servicio");
            }
        } else {
            $this->redireccionar('servicio/listado');
        }
    }

    public function detalle($id) {
        Session::tienePermiso('detalle');
        $servicio = ServicioPrestado::getById($id);
        if ($servicio->getId() != null) {
            $this->view->renderizar("detalle", array("servicio" => $servicio));
        } else {
            $this->redireccionar('servicio/listado');
        }
    }

}

?>

