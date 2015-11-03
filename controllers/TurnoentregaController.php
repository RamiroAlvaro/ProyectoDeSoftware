<?php
include_once('models/turno_entrega.php');

class TurnoentregaController extends Controller {
    
    
        public function index() {
        $this->redireccionar('index');
        }
        
        public function listado() {            
            Session::tienePermiso('alta');
            $this->view->setEncabezado("Listado de turnos de entrega");
            $this->view->setTitulo("Turnos de entrega");                
            $this->view->renderizar("listado", array(                    
                "token" => $this->token,
                "token_id" => $this->token_id));           
        }
        
        public function listarPorFecha() {
            Session::tienePermiso('alta');
            if (isset($_GET['fechaEnvio'])) {
                $fecha = $_GET['fechaEnvio'];
                $turnos = Turnoentrega::obtenerTurnosDeEntrega($fecha);
                foreach ($turnos as $turno){
                    if($turno['con_envio'] == 0){
                        $turno['con_envio'] = "No";
                    }else{
                        $turno['con_envio'] = "Si";
                    }
                    if($turno['entregado'] == 0){
                        $turno['entregado'] = "No";
                    }else{
                        $turno['entregado'] = "Si";
                    }
                    $pedidos[]=$turno;
                }
                echo json_encode($pedidos);
            } 
        }
    
}