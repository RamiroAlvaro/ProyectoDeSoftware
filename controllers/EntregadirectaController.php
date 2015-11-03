<?php

require_once('models/entidad_receptora.php');
require_once('models/detalle.php');
require_once('models/configuracion.php');
require_once ('models/alimento.php');
require_once ('models/entrega_directa.php');
require_once ('models/alimento_entrega_directa.php');

class EntregadirectaController extends Controller{
    
    
        public function index() {
        $this->redireccionar('index');
    }
    
     public function organizarDatos($detalles, $params) {
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
    
    private function validarDatos($datos,&$entregaDirecta ) {
        
        if (!isset($datos['pedidos'])){
            $entregaDirecta->setError('pedidos', 'Seleccione al menos un alimento y una cantidad');
        }
        
        if (isset($datos['pedidos'])){
        foreach ($datos['pedidos'] as $clave => $valor){
            if(($valor == 0) || ($valor == null)){
              $entregaDirecta->setError('cantidad', 'La cantidad debe ser distinta de cero');
              break;
            }
        }
        }
        
    }
    
    private function validar (&$entregaDirecta){
            if ($this->esVacio($entregaDirecta->getEntidad_receptora_id())) {
            $entregaDirecta->setError('entidad', 'Debe seleccionar una entidad');
        }
    }


    public function alta() {
        Session::tienePermiso('alta');
        $this->view->setEncabezado("Nueva entrega directa");
        $this->view->setTitulo("Entrega directa");
        if (isset($_POST['params'])) {
            $this->check_csrf('post');
            $this->actualizar_csrf();
            $datosEntregaDirecta= $_POST["params"];
            $datosEntregaDirecta['fecha']= date('Y-m-d');
            $entregaDirecta = Entregadirecta::nuevo($datosEntregaDirecta);
            $this->validar ($entregaDirecta);
            $datos = $this->organizarDatos($_POST["detalles"], $_POST["params"]);
            $this->validarDatos($datos, $entregaDirecta);
            if (count($entregaDirecta->getErrores()) != 0){
                
                  //creo las clases necesarias para el primer acceso al formulario
            $detalle = new Detalle();
            $entidad = new EntidadReceptora();
            $config = new Configuracion();

            //obtengo todas las entidades, el limite de vencimiento y los detalles disponibles
            $entidades = $entidad->obtenerTodos();
            $dias = $config->buscarPor("clave", "vencimiento_stock");
            $entrega = $detalle->obtenerPorVencer($dias[0]['valor']);

           // $alimentosListado = Alimento::getStockOfall();
            
            $this->view->setError("Modifique los campos indicados.");
            $this->view->renderizar("formulario", array("accion" => "alta",
                "entrega_directa"=>$entregaDirecta,
                "pedidos" => $entrega,
                "entidades" => $entidades,
                "token" => $this->token,
                "token_id" => $this->token_id));
            exit();
            }
            $entregaDirecta->guardar();
            $paramsAlimentoEntregaDirecta['entrega_directa_id'] = $entregaDirecta->getId();
         
            $alimento_pedido = array();
            foreach ($datos["pedidos"] as $key => $val) {

                $alimento_pedido['entrega_directa_id'] = $paramsAlimentoEntregaDirecta['entrega_directa_id'];
                $alimento_pedido['detalle_alimento_id'] = $key;
                $alimento_pedido['cantidad'] = $val;
                $alimento_pedido['baja'] = 0;
                
                $alimentoPedido = Alimentoentregadirecta::inicializar($alimento_pedido);
                $alimentoPedido->guardar();
                
                $detalleAlimento = new Detalle();
                $detalleAli = $detalleAlimento->obtenerDetalleAlimento($key);
                $detalleAlimento = $detalleAlimento->inicializar($detalleAli[0]);
                $stockActual = $detalleAlimento->getStock();
                $auxiliar= $stockActual-$val;
                $detalleAlimento->setStock($auxiliar);
                $detalleAlimento->actualizar();
                
            }

            $detalle = new Detalle();
            $entidad = new EntidadReceptora();
            $config = new Configuracion();

            //obtengo todas las entidades, el limite de vencimiento y los detalles disponibles
            $entidades = $entidad->obtenerTodos();
            $dias = $config->buscarPor("clave", "vencimiento_stock");
            $entrega = $detalle->obtenerPorVencer($dias[0]['valor']);

           // $alimentosListado = Alimento::getStockOfall();
            
            
            $this->view->renderizar("formulario", array("accion" => "alta",
                "pedidos" => $entrega,
                "entidades" => $entidades,
                "token" => $this->token,
                "token_id" => $this->token_id));
        } else {
            //creo las clases necesarias para el primer acceso al formulario
            $detalle = new Detalle();
            $entidad = new EntidadReceptora();
            $config = new Configuracion();

            //obtengo todas las entidades, el limite de vencimiento y los detalles disponibles
            $entidades = $entidad->obtenerTodos();
            $dias = $config->buscarPor("clave", "vencimiento_stock");
            $entrega = $detalle->obtenerPorVencer($dias[0]['valor']);

           // $alimentosListado = Alimento::getStockOfall();
            
            
            $this->view->renderizar("formulario", array("accion" => "alta",
                "pedidos" => $entrega,
                "entidades" => $entidades,
                "token" => $this->token,
                "token_id" => $this->token_id));
        }
    }
}