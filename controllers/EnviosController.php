<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 include_once('models/pedido_modelo.php');  
 
 class EnviosController extends Controller {
     
      public function index() {
        $this->redireccionar('index');
    }
     
     public function listado() {         
        Session::tienePermiso('listado');
        $this->view->setEncabezado("Listado de envíos");
        $this->view->setTitulo("Envíos");
        $envios = Pedidomodelo::obtenerPedidosConEnvio();
        $this->view->renderizar("listado", array("envios" => $envios,
                                                 "token" => $this->token,
                                                 "token_id" => $this->token_id));
        
     }   
     
 }
