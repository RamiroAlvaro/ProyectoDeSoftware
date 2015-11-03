<?php

class Entregadirecta extends Model {
    
    private $id;
    private $entidad_receptora_id;
    private $fecha;
    private $baja;
    private $errores = array();
    
      public static function nuevo ($params, $id = null){

        $entregaDirecta = new self();
        $entregaDirecta->setId($id);
        $entregaDirecta->setEntidad_receptora_id($params['entidad_receptora_id']);
        $entregaDirecta->setFecha($params['fecha']);
        return $entregaDirecta;
        
        
    }
    
     public static function inicializar ($params){
        
        $entregaDirecta = new self();
        $entregaDirecta->setId($params['id']);
        $entregaDirecta->setEntidad_receptora_id($params['entidad_receptora_id']);
        $entregaDirecta->setFecha($params['fecha']);
        $entregaDirecta->setBaja($params['baja']);
        return $entregaDirecta;
    }
    
     public function guardar(){
        $sql = 'INSERT INTO `entrega_directa` (`entidad_receptora_id`, `fecha`) VALUES (:entidad_receptora_id, :fecha)';
        $query = $this->getDb()->prepare($sql);
        $bool = $query->execute(array(':entidad_receptora_id' => $this->getEntidad_receptora_id(),
                                     ':fecha' => $this->getFecha(),
                                   
            
                                    ));
        $this->setId(self::lastInsertId());
        
        return $bool;
        
    }
    
     public static function buscarPor($atributo, $valor) {
        $sql = "select * from entrega_directa where $atributo = :valor";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
       }
       
      public static function obtenerTodos() {
        $sql = 'select * from entrega_directa';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } 
    
    function getId() {
        return $this->id;
    }

    function getEntidad_receptora_id() {
        return $this->entidad_receptora_id;
    }

    function getFecha() {
        return $this->fecha;
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

    function setEntidad_receptora_id($entidad_receptora_id) {
        $this->entidad_receptora_id = $entidad_receptora_id;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setBaja($baja) {
        $this->baja = $baja;
    }

    function setErrores($errores) {
        $this->errores = $errores;
    }
    
     public function setError($clave, $error) {
        $this->errores[$clave] = $error;
    }


}

?>

