<?php

require_once('models/entidad_receptora.php');
require_once('models/estado_pedido.php');
require_once('models/turno_entrega.php');
require_once('models/pedido_modelo.php');
require_once('models/alimento_pedido.php');
require_once('models/detalle.php');
require_once('models/configuracion.php');

class PedidomodeloController extends Controller {

    public function index() {
        $this->redireccionar('index');
    }

    private function organizarDatos($detalles, $params) {
        $aux = array();
        foreach ($detalles as $value) {
            if (isset($value['id'])) {
                $aux["pedidos"][$value['id']] = $value['cantidad'];
            }
        }
        foreach ($params as $key => $value) {
            if ($key == "hora") {
                $aux[$key] = $value . ":00";
            } else {
                $aux[$key] = $value;
            }
        }
        return $aux;
    }
    
    private function validateDate ($date, $format){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
    
    private function validar ($datos){
        
         $errores = array();
        
         if (!isset($datos['pedidos'])){
            $errores['pedidos']= "Seleccione al menos un alimento y una cantidad";
        }
        
        if (isset($datos['pedidos'])){
        foreach ($datos['pedidos'] as $clave => $valor){
            if(($valor == 0) || ($valor == null)){
              $errores['cantidad']="La cantidad debe ser distinta de cero";
              break;
            }
          }
        }
        
        if ($this->esVacio($datos['descripcion'])) {
            $errores['descripcion'] = "Por favor complete el campo descripcion";
        }
        if (strlen($datos['descripcion']) > 20) {
            $errores['descripcion'] = "La descripcion no puede tener más de 20 caracteres";
        }
        if ((preg_match('/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $datos['descripcion'])) == 1) {
            $errores['descripcion'] =  "La descripcion sólo puede contener caracteres alfanuméricos";
        }
        
        if ($this->esVacio($datos['entidad'])) {
            $errores['entidad'] = "Debe seleccionar una entidad";
        }
        
        if ($this->esVacio($datos['fecha'])) {
            $errores['fecha'] = "Debe seleccionar una fecha";
        }
        
        if (!(($this->validateDate($datos['fecha'], 'Y/m/d')))){
            
           
            $errores['fecha'] = "Ingrese un formato de fecha valido";
        }
        
          if ($this->esVacio($datos['hora'])) {
            $errores['hora'] = "Debe seleccionar una hora";
        }
        
         if (!(($this->validateDate($datos['hora'], 'H:i:s')))){
            
           
            $errores['hora'] = "Ingrese un formato de hora valido";
        }
        
        return $errores;
        
    }

    public function alta() {
        Session::tienePermiso('alta');
        $this->view->setEncabezado("Nuevo pedido");
        $this->view->setTitulo("Pedido");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $datos = $this->organizarDatos($_POST["detalles"], $_POST["params"]);
            $errores = $this->validar ($datos);
           
            if (count($errores) != 0){
                
            $detalle = new Detalle();
            $entidad = new EntidadReceptora();

            $entidades = $entidad->obtenerTodos();
            $config = new Configuracion();
            $dias = $config->buscarPor("clave", "vencimiento_stock");
            $pedidos = $detalle->obtenerDisponibles($dias[0]['valor']);
            $this->view->setError("Modifique los campos indicados.");
            $this->view->renderizar("formulario", array("accion" => "alta",
                "errores" => $errores,
                "pedidos" => $pedidos,
                "entidades" => $entidades,
                "token" => $this->token,
                "token_id" => $this->token_id));
                
                exit();
                
            }
            
            $turnoEntrega = Turnoentrega::nuevo($datos);
            $turnoEntrega->guardar();
            $paramsPedidoModelo['turno_entrega_id'] = $turnoEntrega->getId();
            $estadoPedido = Estadopedido::nuevo($datos);
            $estadoPedido->guardar();
            $paramsPedidoModelo['estado_pedido_id'] = $estadoPedido->getId();

            $paramsPedidoModelo['entidad_receptora_id'] = $datos['entidad'];
            if (isset($datos['con_envio'])) {
                $paramsPedidoModelo['con_envio'] = 1;
            } else {
                $paramsPedidoModelo['con_envio'] = 0;
            }
            $paramsPedidoModelo['fecha_ingreso'] = date("Y-m-d");
            $pedidoModelo = Pedidomodelo::nuevo($paramsPedidoModelo);
            $pedidoModelo->guardar();
            $paramsAlimentoPedido['pedido_numero'] = $pedidoModelo->getNumero();
            $alimento_pedido = array();
            foreach ($datos["pedidos"] as $key => $val) {

                $alimento_pedido["pedido_numero"] = $paramsAlimentoPedido['pedido_numero'];
                $alimento_pedido["detalle_alimento_id"] = $key;
                $alimento_pedido["cantidad"] = $val;
                $alimentoPedido = Alimentopedido::inicializar($alimento_pedido);
                $alimentoPedido->guardar();

                $detalleAlimento = new Detalle();
                $detalleAli = $detalleAlimento->obtenerDetalleAlimento($key);
                $detalleAlimento = $detalleAlimento->inicializar($detalleAli[0]);
                $stockActual = $detalleAlimento->getStock();
                $auxiliar = $stockActual - $val;
                $detalleAlimento->setStock($auxiliar);
                $aux = $detalleAlimento->GetReservado();
                $detalleAlimento->setReservado($val + $aux);
                $detalleAlimento->actualizar();
            }

            $detalle = new Detalle();
            $entidad = new EntidadReceptora();

            $entidades = $entidad->obtenerTodos();
            $config = new Configuracion();
            $dias = $config->buscarPor("clave", "vencimiento_stock");
            $pedidos = $detalle->obtenerDisponibles($dias[0]['valor']);

            $this->view->renderizar("formulario", array("accion" => "alta",
                "pedidos" => $pedidos,
                "entidades" => $entidades,
                "token" => $this->token,
                "token_id" => $this->token_id));
        } else {
            $detalle = new Detalle();
            $entidad = new EntidadReceptora();

            $entidades = $entidad->obtenerTodos();
            $config = new Configuracion();
            $dias = $config->buscarPor("clave", "vencimiento_stock");
            $pedidos = $detalle->obtenerDisponibles($dias[0]['valor']);

            $this->view->renderizar("formulario", array("accion" => "alta",
                "pedidos" => $pedidos,
                "entidades" => $entidades,
                "token" => $this->token,
                "token_id" => $this->token_id));
        }
    }

    public function listado() {
        Session::tienePermiso('listado');
        $this->view->setEncabezado("Listado pedidos");
        $this->view->setTitulo("Listado pedidos");
        $pedidoModelo = new Pedidomodelo();
        $pedidos = $pedidoModelo->obtenerTodos();

        $this->view->renderizar("listado", array("accion" => "alta",
            "pedidos" => $pedidos,
            "token" => $this->token,
            "token_id" => $this->token_id));
    }

    public function detalle($numero) {
        Session::tienePermiso('detalle');
        $this->view->setEncabezado("Detalle de pedido");
        $pedido_detalle = Pedidomodelo::getDetalleOf($numero);
        if ($pedido_detalle) {
            $this->view->renderizar("detalle", array("detalles" => $pedido_detalle));
        } else {
            $this->redireccionar('pedido/listado');
        }
    }

    public function eliminar($numero) {
        Session::tienePermiso('eliminar');
        $this->check_csrf('get');
        $this->actualizar_csrf();
        $pedido_modelo = Pedidomodelo::getById($numero);
        if ($pedido_modelo->getNumero() != null) {
            $items = Pedidomodelo::getDetalleOf($numero);
            foreach ($items as $item) {
                $detalleAlimento = new Detalle();
                $detalleAli = $detalleAlimento->obtenerDetalleAlimento($item['detalle_alimento_id']);
                $detalleAlimento = $detalleAlimento->inicializar($item);
                $stockActual = $detalleAlimento->getStock();
                $auxiliar = $stockActual + $item['cantidad'];
                $detalleAlimento->setStock($auxiliar);
                $aux = $detalleAlimento->GetReservado();
                $detalleAlimento->setReservado($aux - $item['cantidad']);
                $detalleAlimento->actualizar();
            }
            if ($pedido_modelo->borrar()) {
                $this->redireccionar('pedidomodelo/listado&m=b');
            } else {
                $this->view->setError("No se pudo eliminar el pedido");
            }
        } else {
            $this->redireccionar('pedidomodelo/listado&m=b');
        }
    }

    public function entregar($id) {
        Session::tienePermiso('entregar');
        $pedido = EstadoPedido::getById($id);
        $pedido->actualizar();
         $pedido_modelo = Pedidomodelo::getById($id);
        if ($pedido_modelo->getNumero() != null) {
            $items = Pedidomodelo::getDetalleOf($id);
            foreach ($items as $item) {
                $detalleAlimento = new Detalle();
                $detalleAli = $detalleAlimento->obtenerDetalleAlimento($item['detalle_alimento_id']);
                $detalleAlimento = $detalleAlimento->inicializar($item);
                $reservadoActual = $detalleAlimento->getReservado();
                $auxiliar = $reservadoActual - $item['cantidad'];
                $detalleAlimento->setReservado($auxiliar);
                $detalleAlimento->actualizar();
            }
        }
        
        $this->redireccionar('pedidomodelo/listado');
     }

}
