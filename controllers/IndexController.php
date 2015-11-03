<?php

require_once 'models/estado_pedido.php';
require_once 'models/configuracion.php';
require_once 'models/detalle.php';
class IndexController extends Controller {
  
    private $info = Array();
   
    
    public function index() {
        $this->linkedin();
        $this->view->setMensaje("bienvenido al Banco de Alimentos");
        $this->view->renderizar("index", array("info" => $this->info));
    }
    public function admin() {
        Session::get('autenticado');
        $this->view->setMensaje("bienvenido al Banco de Alimentos");
        //genero reporte de productos
        $config = new Configuracion();
        $detalle = new Detalle();
        $max_dias = $config->buscarPor("clave", "vencimiento_stock");
        $proximos_a_vencerse = $detalle->getVencidosEn($max_dias[0]['valor']);
        
        //generar pendies
        $pedido = new Estadopedido();
        $hoy = date("Y-m-d");
        $pendientes = $pedido->getPedidosDelDia('fecha', $hoy);
        $hora = date('H:i:s');
        for ( $i = 0; $i < count($pendientes); $i++){
            if (($pendientes[$i]['hora'] < $hora) and (!$pendientes[$i]['entregado'])){
                $pendientes[$i]['tarde']= "RETRASADO";
                $pendientes[$i]['entregado']= "No";
            }else{
                $pendientes[$i]['tarde']= "A TIEMPO";
                $pendientes[$i]['entregado']= "No";
            }
        }
        
        $this->view->renderizar("backend", array("detalles" => $proximos_a_vencerse,
                                                "pendientes" => $pendientes,
                                                "dias" => $max_dias[0]['valor']));
    }
    
    public function linkedin() {
        $oauth = new OAuth("772ot2tfqntox5", "Y6kUKUlWcWc6yDkd");
        $oauth->setToken("94616be2-c292-472d-9f45-dfad9b78f2ef", "cdf34568-ed5f-4a27-a15d-4c8bf276fb4d");
        $params = array();
        $headers = array();
        $method = OAUTH_HTTP_METHOD_GET;
        $url = "https://api.linkedin.com/v1/people/~:(id,first-name,last-name,industry,picture-url,location:(name))?format=json";
        $oauth->fetch($url, $params, $method, $headers);
        $result = json_decode($oauth->getLastResponse(),true);        
        $this->info['nombre'] = $result['firstName'];
        $this->info['apellido'] = $result['lastName'];
        $this->info['foto'] = $result['pictureUrl'];
        $this->info['ubicacion'] = $result['location']['name'];
        $this->info['industria'] = $result['industry'];
  
    }
    
       
}
