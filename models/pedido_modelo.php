<?php

class Pedidomodelo extends Model {

    private $numero;
    private $entidad_receptora_id;
    private $fecha_ingreso;
    private $estado_pedido_id;
    private $turno_entrega_id;
    private $con_envio;
    private $baja;
    private $errores = array();

    public static function nuevo($params, $numero = null) {

        $pedidoModelo = new self();
        $pedidoModelo->setNumero($numero);
        $pedidoModelo->setEntidad_receptora_id($params['entidad_receptora_id']);
        $pedidoModelo->setFecha_ingreso($params['fecha_ingreso']);
        $pedidoModelo->setEstado_pedido_id($params['estado_pedido_id']);
        $pedidoModelo->setTurno_entrega_id($params['turno_entrega_id']);
        $pedidoModelo->setCon_envio($params['con_envio']);
        return $pedidoModelo;
    }

    public static function inicializar($params) {

        $pedidoModelo = new self();
        $pedidoModelo->setNumero($params['numero']);
        $pedidoModelo->setEntidad_receptora_id($params['entidad_receptora_id']);
        $pedidoModelo->setFecha_ingreso($params['fecha_ingreso']);
        $pedidoModelo->setEstado_pedido_id($params['estado_pedido_id']);
        $pedidoModelo->setTurno_entrega_id($params['turno_entrega_id']);
        $pedidoModelo->setCon_envio($params['con_envio']);
        $pedidoModelo->setBaja($params['baja']);
        return $pedidoModelo;
    }

    public function guardar() {
        $sql = 'INSERT INTO `pedido_modelo` (`entidad_receptora_id`, `fecha_ingreso`, `estado_pedido_id`, `turno_entrega_id`, `con_envio` )'
                . ' VALUES (:entidad_receptora_id, :fecha_ingreso, :estado_pedido_id, :turno_entrega_id, :con_envio)';
        $query = $this->getDb()->prepare($sql);
        $bool = $query->execute(array(':entidad_receptora_id' => $this->getEntidad_receptora_id(),
            ':fecha_ingreso' => $this->getFecha_ingreso(),
            ':estado_pedido_id' => $this->getEstado_pedido_id(),
            ':turno_entrega_id' => $this->getTurno_entrega_id(),
            ':con_envio' => $this->getCon_envio()
        ));
        $this->setNumero(self::lastInsertId());
        return $bool;
    }

    public static function getById($numero) {
        $sql = 'select * from pedido_modelo where numero = :numero';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":numero" => $numero));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = Pedidomodelo::inicializar($arreglo[0]);
        return $result;
    }

    public static function obtenerTodos() {
        $sql = 'select pm.baja, pm.numero, er.razon_social, te.fecha, te.hora, ep.entregado, pm.con_envio
                from pedido_modelo pm 
                inner join estado_pedido ep on (pm.estado_pedido_id = ep.id)
                inner join turno_entrega te on (pm.turno_entrega_id = te.id)
                inner join entidad_receptora er on (pm.entidad_receptora_id = er.id)';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarPor($atributo, $valor) {
        $sql = "select * from pedido_modelo where $atributo = :valor";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function obtenerPedidosConEnvio() {
        $sql = "SELECT razon_social, domicilio, fecha FROM entidad_receptora "
                . "INNER JOIN pedido_modelo ON (entidad_receptora.id = pedido_modelo.entidad_receptora_id) "
                . "INNER JOIN turno_entrega ON (pedido_modelo.turno_entrega_id = turno_entrega.id) "
                . "WHERE (con_envio = '1') AND (pedido_modelo.baja = '0')";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function getDetalleOf($numero) {
        $sql = "select *
                from pedido_modelo pm inner join alimento_pedido ap on (pm.numero = ap.pedido_numero)
                inner join detalle_alimento da on (ap.detalle_alimento_id = da.id)
                where numero = :numero";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":numero" => $numero));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function borrar() {
        $sql = 'UPDATE pedido_modelo SET baja = 1 WHERE (numero = :id )';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":id" => $this->getNumero()));
    }


    function getNumero() {
        return $this->numero;
    }

    function getEntidad_receptora_id() {
        return $this->entidad_receptora_id;
    }

    function getFecha_ingreso() {
        return $this->fecha_ingreso;
    }

    function getEstado_pedido_id() {
        return $this->estado_pedido_id;
    }

    function getTurno_entrega_id() {
        return $this->turno_entrega_id;
    }

    function getCon_envio() {
        return $this->con_envio;
    }

    function getBaja() {
        return $this->baja;
    }

    function getErrores() {
        return $this->errores;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setEntidad_receptora_id($entidad_receptora_id) {
        $this->entidad_receptora_id = $entidad_receptora_id;
    }

    function setFecha_ingreso($fecha_ingreso) {
        $this->fecha_ingreso = $fecha_ingreso;
    }

    function setEstado_pedido_id($estado_pedido_id) {
        $this->estado_pedido_id = $estado_pedido_id;
    }

    function setTurno_entrega_id($turno_entrega_id) {
        $this->turno_entrega_id = $turno_entrega_id;
    }

    function setCon_envio($con_envio) {
        $this->con_envio = $con_envio;
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

