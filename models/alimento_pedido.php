<?php

class Alimentopedido extends Model{
    
    private $pedido_numero;
    private $detalle_alimento_id;
    private $cantidad;
    private $baja;
    private $errores = array();
    
    
   
              
    
     public static function inicializar ($params){
        
        $alimentoPedido = new self();
        $alimentoPedido->setPedido_numero($params['pedido_numero']);
        $alimentoPedido->setDetalle_alimento_id($params['detalle_alimento_id']);
        $alimentoPedido->setCantidad($params['cantidad']);

        return $alimentoPedido;
    }
    
      public function guardar(){
        $sql = 'INSERT INTO `alimento_pedido` (`pedido_numero`, `detalle_alimento_id`, `cantidad`) VALUES (:pedido_numero, :detalle_alimento_id, :cantidad)';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(':pedido_numero' => $this->getPedido_numero(),
                                     ':detalle_alimento_id' => $this->getDetalle_alimento_id(),
                                     ':cantidad' => $this->getCantidad(),
            
                                    ));
        
    }
    
       public static function getById($pedido_numero, $detalle_alimento_id) {
        $sql = 'select * from alimento_pedido where pedido_numero = :pedido_numero and detalle_alimento_id = :detalle_alimento_id ';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":pedido_numero" => $pedido_numero, ":detalle_alimento_id" => $detalle_alimento_id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = Alimentopedido::inicializar($arreglo[0]);
        return $result;
    }
    
    public static function obtenerTodos() {
        $sql = 'select * from alimento_pedido';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function buscarPor($atributo, $valor) {
        $sql = "select * from alimento_pedido where $atributo = :valor";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
       }
    
    function getPedido_numero() {
        return $this->pedido_numero;
    }

    function getDetalle_alimento_id() {
        return $this->detalle_alimento_id;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getBaja() {
        return $this->baja;
    }

    function getErrores() {
        return $this->errores;
    }

    function setPedido_numero($pedido_numero) {
        $this->pedido_numero = $pedido_numero;
    }

    function setDetalle_alimento_id($detalle_alimento_id) {
        $this->detalle_alimento_id = $detalle_alimento_id;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setBaja($baja) {
        $this->baja = $baja;
    }

    function setErrores($errores) {
        $this->errores = $errores;
    }


}

?>

