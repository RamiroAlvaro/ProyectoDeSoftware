<?php

require_once 'models/donante.php';

//require_once 'controllers/Validaciones.php';
class DonanteController extends Controller {

     public function index() {
        $this->redireccionar('index');
    }
    
     public function validarCampos(&$donante) {
        $donante->setRazonSocial($this->test_input($donante->getRazonSocial()));
        $donante->setApellidoContacto($this->test_input($donante->getApellidoContacto()));
        $donante->setNombreContacto($this->test_input($donante->getNombreContacto()));
        $donante->setTelefonoContacto($this->test_input($donante->getTelefonoContacto()));
        $donante->setMailContacto($this->test_input($donante->getMailContacto()));
        $donante->setDomicilioContacto($this->test_input($donante->getDomicilioContacto()));
        
        if ($this->esVacio($donante->getRazonSocial())) {
            $donante->setError('razon_social','Por favor complete el campo razon social');
        }   
        elseif (strlen($donante->getRazonSocial())>100) {
            $donante->setError('razon_social','La razon social no puede tener más de 100 caracteres');
        }
        elseif ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $donante->getRazonSocial())) == 1) {
            $donante->setError('razon_social','La razon social sólo puede contener caracteres alfanuméricos');
        }
        
        if ($this->esVacio($donante->getApellidoContacto())) {
            $donante->setError('apellido_contacto','Por favor complete el campo apellido');
        }   
        elseif (strlen($donante->getApellidoContacto())>50) {
            $donante->setError('apellido_contacto','El apellido no puede tener más de 50 caracteres');
        }
        elseif ((preg_match('/^[a-zA-Z][a-zA-Z ]*$/', $donante->getApellidoContacto())) == 0) {
            $donante->setError('apellido_contacto','El apellido sólo puede contener letras y espacios');
        }
        
        if ($this->esVacio($donante->getNombreContacto())) {
            $donante->setError('nombre_contacto','Por favor complete el campo nombre');
        }   
        elseif (strlen($donante->getNombreContacto())>50) {
            $donante->setError('nombre_contacto','El nombre no puede tener más de 50 caracteres');
        }
        elseif ((preg_match('/^[a-zA-Z][a-zA-Z ]*$/', $donante->getNombreContacto())) == 0) {
            $donante->setError('nombre_contacto','El nombre sólo puede contener letras y espacios');
        }
        
        if ($this->esVacio($donante->getTelefonoContacto())) {
            $donante->setError('telefono_contacto','Por favor complete el campo telefono');
        }   
        elseif (strlen($donante->getTelefonoContacto())>30) {
            $donante->setError('telefono_contacto','El telefono no puede tener más de 30 caracteres');
        }
        elseif ((preg_match('/^[0-9]*$/', $donante->getTelefonoContacto())) == 0) {
            $donante->setError('telefono_contacto','El telefono sólo puede contener números');
        }
        
        if ($this->esVacio($donante->getMailContacto())) {
            $donante->setError('mail_contacto','Por favor complete el campo e-mail');
        }   
        elseif (strlen($donante->getMailContacto())>50) {
            $donante->setError('mail_contacto','El e-mail no puede tener más de 50 caracteres');
        }
        elseif (!filter_var($donante->getMailContacto(), FILTER_VALIDATE_EMAIL)) {
            $donante->setError('mail_contacto','Ingrese un e-mail valido');
        }
        
         if ($this->esVacio($donante->getDomicilioContacto())) {
            $donante->setError('domicilio_contacto','Por favor complete el campo domicilio');
        }   
        elseif (strlen($donante->getDomicilioContacto())>30) {
            $donante->setError('domicilio_contacto','El domicilio no puede tener más de 200 caracteres');
        }
        elseif ((preg_match('/[#$%^&*()+=\[\]\';,.\/{}|":<>?~\\\\]/', $donante->getDomicilioContacto())) == 1) {
            $donante->setError('domicilio_contacto','El domicilio sólo puede contener caracteres alfanuméricos');
    }
   }

    public function alta() {
        Session::tienePermiso('alta');
        $this->view->setEncabezado("Nuevo donante");
        $this->view->setTitulo("Donante");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $donante = Donante::nuevo($_POST['params']['donante']);
            $this->validarCampos($donante);
            if (count($donante->getErrores()) == 0) {
                $repetido = Donante::buscarPor('razon_social', $donante->getRazonSocial());
                if ((sizeof($repetido) == 0)) {
                    if (!$donante->guardar()) {
                        $this->view->setError("no puedo guardarse el donante.");
                    }
                } else {
                    $this->view->setError("el donante ya existe");
                }
            } else {
                $this->view->setError("Modifique los campos indicados.");
            }
            if ($this->view->getError() != null) {
                $this->view->renderizar("formulario", array("donante" => $donante,
                                                            "accion" => "alta",
                                                            "token" => $this->token,
                                                            "token_id" => $this->token_id));
            } else {
                header('Location: /?uri=donante/listado/&m=a');
            }
        } else {
            $this->view->renderizar("formulario", array("accion" => "alta",
                                                        "encabezado" => "Nuevo Donante",
                                                        "token" => $this->token,
                                                        "token_id" => $this->token_id));
        }
    }

    public function listado($args = null) {
        Session::tienePermiso('listado');
        $donantes = Donante::obtenerTodos();
        $this->view->setEncabezado("Listado donantes");
        $this->view->setTitulo("Donante");
        
         $msj='';
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
        
        $this->view->renderizar("listado", array("donantes" => $donantes, "host" => BASE_URL,
                                                 "token" => $this->token,
                                                 "token_id" => $this->token_id));
    }

    public function modificar($id) {
        Session::tienePermiso('modificar');
        $this->view->setEncabezado("Modificar donante");
        $this->view->setTitulo("Donante");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $donanteModif = Donante::nuevo($_POST['params']['donante']);
            $this->validarCampos($donanteModif);
            $modif = $_POST['params']['donante'];
            $donante = Donante::getById($id);
            if (count($donanteModif->getErrores()) == 0) {
                $repetido = Donante::buscarPor('razon_social', $modif['razon_social']);
                if ((sizeof($repetido) == 0) || ($repetido[0]['id'] == $id)) {
                    $donante->setRazonSocial($modif['razon_social']);
                    $donante->setApellidoContacto($modif['apellido_contacto']);
                    $donante->setNombreContacto($modif['nombre_contacto']);
                    $donante->setTelefonoContacto($modif['telefono_contacto']);
                    $donante->setMailContacto($modif['mail_contacto']);
                    $donante->setDomicilioContacto($modif['domicilio_contacto']);
                    if (!$donante->actualizar()) {
                       $this->view->setError("no puedo actualizarse el donante.");
                    }
                } else {
                    $this->view->setError("el donante ya existe");
                }
                if ($this->view->getError() != null) {
                    $this->view->renderizar("formulario", array("donante" => $donante,
                                                                "accion" => "modificar",
                                                                "encabezado" => "Modificar donante",
                                                                "token" => $this->token,
                                                                "token_id" => $this->token_id ));
                } else {
                    header('Location: /?uri=donante/listado/&m=c');
                }
            }
            else {
                $this->view->setError("Modifique los campos indicados.");
                $donanteModif->setId($id);
                $this->view->renderizar("formulario", array("donante" => $donanteModif,
                    "accion" => "modificar",
                    "encabezado" => "Modificar donante",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            }
        } else {
            $donante = Donante::getById($id);
            if ($donante->getId() != null) {
                $this->view->renderizar("formulario", array("donante" => $donante,
                    "accion" => "modificar",
                    "encabezado" => "Modificar donante",
                    "token" => $this->token,
                     "token_id" => $this->token_id));
            } else {
                header('Location: /?uri=donante/listado/notFound');
            }
        }
    }


    public function eliminar($id) { 
        Session::tienePermiso('eliminar');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        $donante = Donante::getById($id);
        if ($donante->getId() != null) {
            $donante->borrar();
            header('Location: /?uri=donante/listado/&m=b');
        } else {
            header('Location: /?uri=donante/listado/notFound');
        }
    }

    public function detalle($id) {
        Session::tienePermiso('detalle');
        $donante = Donante::getById($id);
        if ($donante->getId() != null) {
            $this->view->renderizar("detalle", array("donante" => $donante));
        } else {
            header('Location: /?uri=donante/listado/notFound');
        }
    }

}

?>