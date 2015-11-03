<?php

class Alimentoentregadirecta extends Model{
    
    private $entrega_directa_id;
    private $detalle_alimento_id;
    private $cantidad;
    private $baja;
    private $errores = array();
    
    public static function inicializar ($params){
        
        $alimentoEntregaDirecta = new self();
        $alimentoEntregaDirecta->setEntrega_directa_id($params['entrega_directa_id']);
        $alimentoEntregaDirecta->setDetalle_alimento_id($params['detalle_alimento_id']);
        $alimentoEntregaDirecta->setCantidad($params['cantidad']);
        $alimentoEntregaDirecta->setBaja($params['baja']);
        return $alimentoEntregaDirecta;
    }
    
     public function guardar(){
        $sql = 'INSERT INTO `alimento_entrega_directa` (`entrega_directa_id`, `detalle_alimento_id`, `cantidad`) VALUES (:entrega_directa_id, :detalle_alimento_id, :cantidad)';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(':entrega_directa_id' => $this->getEntrega_directa_id(),
                                     ':detalle_alimento_id' => $this->getDetalle_alimento_id(),
                                     ':cantidad' => $this->getCantidad(),
            
                                    ));
        
    }
    
    public static function getById($entrega_directa_id, $detalle_alimento_id) {
        $sql = 'select * from alimento_entrega_directa where entrega_directa_id = :entrega_directa_id and detalle_alimento_id = :detalle_alimento_id ';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":entrega_directa_id" => $entrega_directa_id, ":detalle_alimento_id" => $detalle_alimento_id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = Alimentoentregadirecta::inicializar($arreglo[0]);
        return $result;
    }
    
     public static function obtenerTodos() {
        $sql = 'select * from alimento_entrega_directa';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function buscarPor($atributo, $valor) {
        $sql = "select * from alimento_entrega_directa where $atributo = :valor";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
       }
            
    function getEntrega_directa_id() {
        return $this->entrega_directa_id;
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

    function setEntrega_directa_id($entrega_directa_id) {
        $this->entrega_directa_id = $entrega_directa_id;
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