<?php

class Turnoentrega extends Model{
    
    private $id;
    private $fecha;
    private $hora;
    private $baja;
    private $errores = array();
    


    
        public static function nuevo ($params, $id = null){
            $turnoEntrega = new self();
            $turnoEntrega->setId($id);
            $turnoEntrega->setFecha($params['fecha']);
            $turnoEntrega->setHora($params['hora']);
            return $turnoEntrega;
        }
    
    public static function inicializar ($params){
        
        $turnoEntrega = new self();
        $turnoEntrega->setId($params['id']);
        $turnoEntrega->setFecha($params['fecha']);
        $turnoEntrega->setHora($params['hora']);
        $turnoEntrega->setBaja($params['baja']);
        return $turnoEntrega;
    }
    
    public function guardar(){
        $sql = 'INSERT INTO `turno_entrega` (`fecha`, `hora`) VALUES (:fecha, :hora)';
        $query = $this->getDb()->prepare($sql);
        $bool = $query->execute(array(':fecha' => $this->getFecha(),
                                     ':hora' => $this->getHora(),    
                                    ));
        $this->setId(self::lastInsertId());
        
        return $bool;
    }
    

    
   
     public static function buscarPor($atributo, $valor) {
        $sql = "select * from turno_entrega where $atributo = :valor";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
       }
       
     public static function obtenerTodos() {
        $sql = 'select * from turno_entrega';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }  
    
    public static function obtenerTurnosDeEntrega($fecha) {
        $sql = 'SELECT numero, entregado, hora, razon_social, con_envio
                FROM turno_entrega
                INNER JOIN pedido_modelo ON (turno_entrega.id = pedido_modelo.numero)
                INNER JOIN entidad_receptora ON (pedido_modelo.entidad_receptora_id = entidad_receptora.id)
                INNER JOIN estado_pedido on (pedido_modelo.estado_pedido_id = estado_pedido.id)
                WHERE fecha = :fecha and pedido_modelo.baja = 0';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":fecha" => $fecha));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    function getId() {
        return $this->id;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getHora() {
        return $this->hora;
    }

    function getBaja() {
        return $this->baja;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setHora($hora) {
        $this->hora = $hora;
    }

    function setBaja($baja) {
        $this->baja = $baja;
    }
    
    function getErrores() {
        return $this->errores;
    }

    function setErrores($errores) {
        $this->errores = $errores;
    }


    
    
}



?>

