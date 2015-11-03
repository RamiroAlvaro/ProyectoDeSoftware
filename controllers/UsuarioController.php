<?php

require_once 'models/usuario.php';
require_once 'models/usuario.php';

//require_once 'controllers/Validaciones.php';
class UsuarioController extends Controller {

    public function index() {
        $this->redireccionar('index');
    }

    private function validarCampos(&$usuario) {
        if ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $usuario->getUsername())) == 1) {
            $usuario->setError('username', 'El username sólo puede contener caracteres alfanuméricos');
        }
        if ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $usuario->getRol())) == 1) {
            $usuario->setError('Rol', 'El rol es obligatorio');
        }
        if (isset($_POST['passActual'])) {
            if ($usuario->getClave() != md5($_POST['passActual'])) {
                $usuario->setError('Original', 'la clave actual no coincide');
            }
            if ($usuario->getClave() != $usuario->getReclave()) {
                $usuario->setError('igualdad', 'las claves deben ser iguales');
            }
        } else {
            if ($usuario->getClave() != $usuario->getReclave()) {
                $usuario->setError('igualdad', 'las claves deben ser iguales');
            }
        }
    }
    private function validarCamposModif($usuario, $usuarioModif) {
 
        if ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $usuario->getUsername())) == 1) {
            $usuarioModif->setError('username', 'El username sólo puede contener caracteres alfanuméricos');
        }
        if ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $usuario->getRol())) == 1) {
            $usuarioModif->setError('Rol', 'El rol es obligatorio');
        }
        if (isset($_POST['params']['usuario']['passActual'])) {
            $actual = md5($_POST['params']['usuario']['passActual']);
            if ($usuario->getClave() != $actual) {
                $usuarioModif->setError('Original', 'la clave actual no coincide');
            }
            if ($usuarioModif->getClave() != $usuarioModif->getReclave()) {
                $usuarioModif->setError('igualdad', 'las claves deben ser iguales');
            }
        } else {
            if ($usuarioModif->getClave() != $usuarioModif->getReclave()) {
                $usuarioModif->setError('igualdad', 'las claves deben ser iguales');
            }
        }
    }
    private function guardar($usuario) {
        if (count($usuario->getErrores()) == 0) {
            $repetido = Usuario::buscarPor('username', $usuario->getUserName());
            if ((sizeof($repetido) == 0)) {
                if ($usuario->guardar()) {
                    if (!$usuario->asociar()) {
                        $this->view->setError("no puedo asociarse los permisos.");
                    }
                } else {
                    $this->view->setError("no puedo guardarse el usuario.");
                }
            } else {
                $this->view->setError("el username ya existe");
            }
        } else {
            $this->view->setError("Modifique los campos indicados.");
        }
    }

    public function alta() {
        Session::acceso('admin');
        $this->view->setEncabezado("Nuevo usuario");
        $this->view->setTitulo("Usuario");
        $roles = Usuario::obtener_roles();
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $params = $_POST['params']['usuario'];
            $usuario = Usuario::nuevo($_POST['params']['usuario']);
            $this->validarCampos($usuario);
            $this->guardar($usuario);
            if ($this->view->getError() != null) {
                $this->view->renderizar("formulario", array("usuario" => $usuario,
                    "roles" => $roles,
                    "accion" => "alta"));
            } else {
                $this->redireccionar('usuario/listado/&m=a');
            }
        } else {
            $this->view->renderizar("formulario", array("accion" => "alta",
                "roles" => $roles,
                "token" => $this->token,
                "token_id" => $this->token_id));
        }
    }

    public function listado($args = null) {
        Session::acceso('admin');
        $this->view->setEncabezado("Listado usuarios");
        $this->view->setTitulo("Usuario");
        $usuarios = Usuario::obtenerTodos();
        $this->view->setTitulo("Usuario");
        //mensaje abm exitosa
        $msj = '';
        $this->cartelito();
        //fin mensaje abm exitosa
        $this->view->renderizar("listado", array("usuarios" => $usuarios,
            "mensaje" => $msj,
            "token" => $this->token,
            "token_id" => $this->token_id));
    }

    public function modificar($id) {
        Session::acceso('admin');
        $this->view->setEncabezado("Modificar usuario");
        $this->view->setTitulo("Usuario");
        $roles = Usuario::obtener_roles();
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $usuarioModif = Usuario::nuevo($_POST['params']['usuario']);
            $usuario = Usuario::getById($id);
            $this->validarCamposModif($usuario, $usuarioModif);
            $modif = $_POST['params']['usuario'];
            $usuario = Usuario::getById($id);


            if (count($usuarioModif->getErrores()) == 0) {
                $repetido = Usuario::buscarPor('username', $usuarioModif->getUsername());
                if ((sizeof($repetido) == 0) || ($repetido[0]['id'] == $id)) {
                    $usuarioModif->setId($usuario->getId());
                    if ($usuarioModif->actualizar()) {
                        if (!$usuarioModif->reAsociar()) {
                            $this->view->setError("no puedo asociarse los permisos.");
                        }
                    } else {
                        $this->view->setError("no puedo actualizarse el usuario.");
                    }
                } else {
                    $this->view->setError("el codigo ya existe");
                }
                if ($this->view->getError() != null) {
                    $this->view->renderizar("formulario", array("usuario" => $usuario,
                        "roles" => $roles,
                        "accion" => "modificar",
                        "token" => $this->token,
                        "actual" => "vacio",
                        "token_id" => $this->token_id));
                } else {
                    $this->redireccionar('usuario/listado/&m=c');
                }
            } else {
                $this->view->setError("Modifique los campos indicados.");
                $usuarioModif->setId($id);
                $this->view->renderizar("formulario", array("usuario" => $usuarioModif,
                    "roles" => $roles,
                    "actual" => "vacio",
                    "accion" => "modificar",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            }
        } else {
            $usuario = Usuario::getById($id);
            if ($usuario->getId() != null) {
                $this->view->renderizar("formulario", array("usuario" => $usuario,
                    "actual" => "vacio",
                    "roles" => $roles,
                    "accion" => "modificar",
                    "token" => $this->token,
                    "token_id" => $this->token_id));
            } else {
                $this->redireccionar('usuario/listado&m=c');
            }
        }
    }

    public function eliminar($id = null) {
        Session::acceso('admin');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        $usuario = Usuario::getById($id);
        if ($usuario->getId() != null) {
            if ($usuario->borrar()) {
                $this->redireccionar('usuario/listado&m=b');
            } else {
                $this->view->setError("No se pudo eliminar el usuario");
            }
        } else {
            $this->redireccionar('usuario/listado');
        }
    }

    public function detalle($id) {
        Session::acceso('admin');
        $this->view->setEncabezado("Detalle de usuario");
        $usuario = Usuario::getById($id);
        if ($usuario) {
            $this->view->renderizar("detalle", array("usuario" => $usuario));
        } else {
            $this->redireccionar('usuario/listado');
        }
    }

}

?>
