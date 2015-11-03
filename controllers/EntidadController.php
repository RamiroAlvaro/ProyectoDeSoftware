<?php

    require_once 'models/entidad_receptora.php';
    require_once 'models/estado_entidad.php';
    require_once 'models/necesidad_entidad.php';
    require_once 'models/servicio_prestado.php';
    
    class EntidadController extends Controller {
        
     public function index() {
        $this->redireccionar('index');
    }
    
     public function validarCampos(&$entidad) {
        $entidad->setRazon_social($this->test_input($entidad->getRazon_social()));
        $entidad->setTelefono($this->test_input($entidad->getTelefono()));
        $entidad->setDomicilio($this->test_input($entidad->getDomicilio()));
        $entidad->setEstado_entidad_id($this->test_input($entidad->getEstado_entidad_id()));
        $entidad->setNecesidad_entidad_id($this->test_input($entidad->getNecesidad_entidad_id()));
        $entidad->setServicio_prestado_id($this->test_input($entidad->getServicio_prestado_id()));
        
        if ($this->esVacio($entidad->getRazon_social())) {
            $entidad->setError('razon_social','Por favor complete el campo razon social');
        }   
        elseif (strlen($entidad->getRazon_social())>100) {
            $entidad->setError('razon_social','La razon social no puede tener más de 100 caracteres');
        }
        elseif ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $entidad->getRazon_social())) == 1) {
            $entidad->setError('razon_social','La razon social sólo puede contener caracteres alfanuméricos');
        }
        
        if ($this->esVacio($entidad->getTelefono())) {
            $entidad->setError('telefono','Por favor complete el campo telefono');
        }   
        elseif (strlen($entidad->getTelefono())>30) {
            $entidad->setError('telefono','El telefono no puede tener más de 30 caracteres');
        }
        elseif ((preg_match('/^[0-9]*$/', $entidad->getTelefono())) == 0) {
            $entidad->setError('telefono','El telefono sólo puede contener números');
        }
        
        if ($this->esVacio($entidad->getDomicilio())) {
            $entidad->setError('domicilio','Por favor complete el campo domicilio');
        }   
        elseif (strlen($entidad->getDomicilio())>200) {
            $entidad->setError('domicilio','El domicilio no puede tener más de 200 caracteres');
        }
        elseif ((preg_match('/[#$%^&*()+=\[\]\';,.\/{}|":<>?~\\\\]/', $entidad->getDomicilio())) == 1) {
            $entidad->setError('domicilio','El domicilio sólo puede contener caracteres alfanuméricos');
        }
        
         if ($this->esVacio($entidad->getEstado_entidad_id())) {
            $entidad->setError('estado_entidad_id','Por favor complete el campo estado entidad');
        }
        
        if ($this->esVacio($entidad->getNecesidad_entidad_id())) {
            $entidad->setError('necesidad_entidad_id','Por favor complete el campo necesidad entidad');
        }
        
        if ($this->esVacio($entidad->getServicio_prestado_id())) {
            $entidad->setError('servicio_prestado_id','Por favor complete el campo servicio entidad');
        } 
              
   }
   
        public function alta() {
        Session::tienePermiso('alta');
            $this->view->setEncabezado("Nueva entidad");
            $this->view->setTitulo("Entidad");
            $estados = EstadoEntidad::obtenerTodos();
            $necesidades = NecesidadEntidad::obtenerTodos();
            $servicios = ServicioPrestado::obtenerTodos();  
            if (isset($_POST['params'])) {
                $this->check_csrf('post');
                $this->actualizar_csrf();
                $entidad = EntidadReceptora::nuevo($_POST['params']['entidad']); 
                $this->validarCampos($entidad);
                if (count($entidad->getErrores()) == 0) {
                    $repetido = EntidadReceptora::buscarPor('razon_social', $entidad->getRazon_social());
                    if ((sizeof($repetido) == 0)) {
                        if (!$entidad->guardar()) {
                            $this->view->setError("no puedo guardarse la entidad receptora.");
                        }
                    } else {
                        $this->view->setError("La razon social ya existe");
                    }
                } else {
                    $this->view->setError("Modifique los campos indicados.");
                }
                if ($this->view->getError() != null) {
                    $this->view->renderizar("formulario", array("entidad" => $entidad,
                        "accion" => "alta",
                    "servicios" => $servicios, "estados" => $estados,
                    "necesidades" => $necesidades,
                    "token" => $this->token,
                    "token_id" => $this->token_id));
                } else {
                    $this->redireccionar('entidad/listado/&m=a');
                }
            } else {
                $this->view->renderizar("formulario", array("accion" => "alta",
                    "servicios" => $servicios, "estados" => $estados,
                    "necesidades" => $necesidades,
                    "token" => $this->token,
                    "token_id" => $this->token_id));
        }
    }
    
    public function listado($args = null) {
        Session::tienePermiso('listado');
        /* Obtiene todas las entidades */
        $entidades = EntidadReceptora::obtenerTodos();
        $msj='';
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
        $this->view->setEncabezado("Listado entidades");
        $this->view->setTitulo("Entidad");
        $this->view->renderizar("listado", array("entidades" => $entidades,
                                                 "token" => $this->token,
                                                 "token_id" => $this->token_id));
    }
     
    public function modificar($id) {
        Session::tienePermiso('entidad_modificar');
        $this->view->setEncabezado("Modificar entidad");
        $this->view->setTitulo("Entidad");
        $estados = EstadoEntidad::obtenerTodos();
        $necesidades = NecesidadEntidad::obtenerTodos();
        $servicios = ServicioPrestado::obtenerTodos();  
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $entidadModif = EntidadReceptora::nuevo($_POST['params']['entidad']);
            $this->validarCampos($entidadModif);
            $modif = $_POST['params']['entidad'];            
            $entidad = EntidadReceptora::getById($id);
            if (count($entidadModif->getErrores()) == 0) {
                $repetido = EntidadReceptora::buscarPor('razon_social', $modif['razon_social']);           
                if ((sizeof($repetido) == 0) || ($repetido[0]['id'] == $id)) {
                    $entidad->setRazon_social($modif['razon_social']);
                    $entidad->setTelefono($modif['telefono']);
                    $entidad->setDomicilio($modif['domicilio']);
                    $entidad->setEstado_entidad_id($modif['estado_entidad_id']);
                    $entidad->setNecesidad_entidad_id($modif['necesidad_entidad_id']);
                    $entidad->setServicio_prestado_id($modif['servicio_prestado_id']);
                    if (!$entidad->actualizar()) {
                        $this->view->setError("no puedo actualizarse la entidad.");
                    }
                } else {
                    $this->view->setError("La razón social ya existe");
                }
                if ($this->view->getError() != null) {
                    $this->view->renderizar("formulario", array("entidad" => $entidad,
                                                                "estados" => $estados,
                                                                "necesidades" => $necesidades,
                                                                "servicios" => $servicios,                                                                 
                                                                "accion" => "modificar",
                                                                "encabezado" => "Modificar entidad receptora",
                                                                "token" => $this->token,
                                                                "token_id" => $this->token_id));
                
                }
                else {
                    $this->redireccionar('entidad/listado/&m=c');
                }
            }else {
                $this->view->setError("Modifique los campos indicados.");
                $entidadModif->setId($id);
                $this->view->renderizar("formulario", array("entidad" => $entidadModif,
                    "accion" => "modificar",
                    "servicios" => $servicios, "estados" => $estados,
                    "necesidades" => $necesidades,
                    "token" => $this->token,
                    "token_id" => $this->token_id ));
            
            }
                } else {
            $entidad = EntidadReceptora::getById($id);
            if ($entidad->getId() != null) {
                $this->view->renderizar("formulario", array("entidad" => $entidad,
                                                            "estados" => $estados,
                                                            "necesidades" => $necesidades,
                                                            "servicios" => $servicios,
                                                            "accion" => "modificar",
                                                            "encabezado" => "Modificar entidad receptora",
                                                            "token" => $this->token,
                                                            "token_id" => $this->token_id));
            } else {
                $this->redireccionar('entidad/listado');
            }
        }
    
    }   
        
    public function eliminar($id = null) {
        Session::tienePermiso('eliminar');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        $alimento = EntidadReceptora::getById($id);
        if ($alimento->getId() != null) {
            if($alimento->borrar()){
                $this->redireccionar('entidad/listado/&m=b');
            }else{
                $this->view->setError("No se pudo eliminar la entidad receptora");
            }
        } else {
            $this->redireccionar('entidad/listado');
        }
    }
    
     public function detalle($id) {
        Session::tienePermiso('detalle');
        $entidad = EntidadReceptora::getById($id);
        $n = NecesidadEntidad::getById($entidad->getNecesidad_entidad_id());
        $e = EstadoEntidad::getById($entidad->getEstado_entidad_id());
        $s =  ServicioPrestado::getById($entidad->getServicio_prestado_id());
        $elementos['necesidad'] = $n->getDescripcion();
        $elementos['estado'] = $e->getDescripcion();
        $elementos['servicio'] = $s->getDescripcion();
        if ($entidad->getId() != null) {
            $this->view->renderizar("detalle", array("entidad" => $entidad,
                                                     "elementos" => $elementos));
        } else {
            $this->redireccionar('entidad/listado');
        }
    }
    
    
        
        
    }