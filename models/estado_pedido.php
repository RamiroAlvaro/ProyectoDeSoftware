<?php

class Estadopedido extends Model{
    
    private $id;
    private $descripcion;
    private $entregado;
    private $baja;
    private $errores = array();
    
      public static function nuevo ($params, $id = null){
        $estadoPedido = new self();
        $estadoPedido->setId($id);
        $estadoPedido->setDescripcion($params['descripcion']);
        return $estadoPedido;
      }
      
       public static function inicializar ($params){
        
        $estadoPedido = new self();
        $estadoPedido->setId($params['id']);
        $estadoPedido->setDescripcion($params['descripcion']);
        $estadoPedido->setEntregado($params['entregado']);
        $estadoPedido->setBaja($params['baja']);
        return $estadoPedido;
    }
    
     public function guardar(){
        $sql = 'INSERT INTO `estado_pedido` (`descripcion`) VALUES (:descripcion)';
        $query = $this->getDb()->prepare($sql);
        $bool= $query->execute(array(':descripcion' => $this->getDescripcion(),
                                     
                                   ));
        
        $this->setId(self::lastInsertId());                           
        
        return $bool;
     }
     
      public static function getById($id) {
        $sql = 'select * from estado_pedido where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = Estadopedido::inicializar($arreglo[0]);
        return $result;
    }
    
    public static function obtenerTodos() {
        $sql = 'select * from estado_pedido';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function buscarPor($atributo, $valor) {
        $sql = "select * from estado_pedido where $atributo = :valor";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
       }
       
       public static function getPedidosDelDia($atributo, $valor) {
        $sql = "select * from estado_pedido e inner join pedido_modelo p ON (e.id = p.estado_pedido_id) inner join turno_entrega t ON (p.turno_entrega_id = t.id) where $atributo = :valor and entregado = 0 and pm.baja = 0";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
       }
       
     public function actualizar() {
        $sql = 'UPDATE estado_pedido '
                . 'SET entregado = :entregado '
                . 'WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(':entregado' => 1,
                                    ':id' => $this->getId()));
    }
     
    function getId() {
        return $this->id;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getEntregado() {
        return $this->entregado;
    }

    function getBaja() {
        return $this->baja;
    }

    function getErrores() {
        return $this->errores;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setEntregado($entregado) {
        $this->entregado = $entregado;
    }

    function setBaja($baja) {
        $this->baja = $baja;
    }

    function setErrores($errores) {
        $this->errores = $errores;
    }


}

?>

