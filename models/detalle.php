<?php

class Detalle extends Model {

    private $id;
    private $alimento_codigo;
    private $fecha_vencimiento;
    private $contenido;
    private $peso_paquete;
    private $stock;
    private $reservado;
    private $baja;
    private $errores = array();

    public static function nuevo($params, $id = null) {
        $detalle_alimento = new self();
        $detalle_alimento->setAlimento_codigo($params['alimento_codigo']);
        $detalle_alimento->setFecha_vencimiento($params['fecha_vencimiento']);
        $detalle_alimento->setContenido($params['contenido']);
        $detalle_alimento->setPeso_paquete($params['peso']);
        $detalle_alimento->setStock($params['stock']);
        $detalle_alimento->setReservado($params['reservado']);
        $detalle_alimento->setId($id);
        return $detalle_alimento;
    }
    
     public static function inicializar($params) {
        $detalle_alimento = new self();
        $detalle_alimento->setAlimento_codigo($params['alimento_codigo']);
        $detalle_alimento->setFecha_vencimiento($params['fecha_vencimiento']);
        $detalle_alimento->setContenido($params['contenido']);
        $detalle_alimento->setPeso_paquete($params['peso_paquete']);
        $detalle_alimento->setStock($params['stock']);
        $detalle_alimento->setReservado($params['reservado']);
        $detalle_alimento->setId($params['id']);
        $detalle_alimento->setBaja($params['baja']);
        return $detalle_alimento;
    }

    public function guardar() {
        $sql = 'INSERT INTO `detalle_alimento`(`alimento_codigo`, `fecha_vencimiento`, `contenido`, `peso_paquete`, `stock`, `reservado`)'
                . ' VALUES (:alimento_codigo, :fecha_vencimiento, :contenido, :peso_paquete, :stock, :reservado)';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(':alimento_codigo' => $this->getAlimento_codigo(),
                    ':contenido' => $this->getContenido(),
                    ':peso_paquete' => $this->getPeso_paquete(),
                    ':stock' => $this->getStock(),
                    ':reservado' => $this->getReservado(),
                    ':fecha_vencimiento' => $this->getFecha_vencimiento()
        ));
    }

    public static function borrar($id) {
        $sql = 'DELETE from alimento_donante WHERE detalle_alimento_id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $sql = 'DELETE from detalle_alimento WHERE id = :id';
        $query = self::nuevaDb()->prepare($sql);
        return $query->execute(array(":id" => $id));
    }

    public function actualizar() {
        $sql = 'UPDATE detalle_alimento '
                . 'SET alimento_codigo = :alimento_codigo, fecha_vencimiento = :fecha_vencimiento '
                . ', contenido = :contenido, peso_paquete = :peso_paquete '
                . ', stock = :stock, reservado = :reservado '
                . 'WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(':alimento_codigo'=> $this->getAlimento_codigo(),
                                    ':fecha_vencimiento'=>  $this->getFecha_vencimiento(),
                                    ':contenido'=>  $this->getContenido(),
                                    ':peso_paquete'=>  $this->getPeso_paquete(),
                                    ':stock'=>  $this->getStock(),
                                    ':reservado' => $this->getReservado(),
                                    ':id'=>  $this->getId()));
    }
    


    public function obtenerId($id) {
        $sql = 'select * from detalle_alimento';
        $query = $this->db->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
        public function obtenerDetalleAlimento($id) {
        $sql = 'select * from detalle_alimento WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVencidosEn($dias) {
        $hoy = date("Y-m-d");
        $max = date('Y-m-d', strtotime("+" . $dias . " days"));
        $sql = 'select * from detalle_alimento where fecha_vencimiento between :hoy and :max';
        $query = $this->getDb()->prepare($sql);
        $query->execute(array(":hoy" => $hoy, ":max" => $max));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodos() {
        $sql = 'select * from detalle_alimento';
        $query = $this->db->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDisponibles($dias) {
        $fecha_min = date('Y-m-d', strtotime("+" . $dias . " days"));
        $sql = "select * from detalle_alimento where stock > 0 and fecha_vencimiento >= '".$fecha_min."'";
        $query = $this->getDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorVencer($dias) {
        $fecha_min = date('Y-m-d', strtotime("+" . $dias . " days"));
        $sql = "select * from detalle_alimento where stock > 0 and fecha_vencimiento >= CURDATE() and fecha_vencimiento <= '".$fecha_min."'";
        $query = $this->getDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerVencidos() {        
        $sql = "SELECT alimento_codigo, fecha_vencimiento, stock "
                . " FROM detalle_alimento "
                . " WHERE (stock > 0) AND (fecha_vencimiento < CURDATE())";
        $query = $this->getDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPor($tabla, $valor) {
        $sql = "select * from detalle_alimento where $tabla = :valor ;";
        $query = $this->db->prepare($sql);
        $query->execute(array(":valor" => $valor));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* buscador generico de todos los elementos que en determinado atributo tengan cierto valor. */

    public static function getDetalleOf($codigo) {
//        $sql = "select * from detalle_alimento where alimento_codigo = :alimento_codigo;";
        $sql = "select * 
                from alimento_donante 
                inner join detalle_alimento on (alimento_donante.detalle_alimento_id = detalle_alimento.id)
                inner join donante on (alimento_donante.donante_id = donante.id)
                where alimento_codigo = :alimento_codigo";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":alimento_codigo" => $codigo));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function exist($id) {
        $sql = 'select * from detalle_alimento where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        return $query->execute(array(":id" => $id));
    }

    public function getId() {
        return $this->id;
    }

    public function getAlimento_codigo() {
        return $this->alimento_codigo;
    }

    public function getFecha_vencimiento() {
        return $this->fecha_vencimiento;
    }

    public function getContenido() {
        return $this->contenido;
    }

    public function getPeso_paquete() {
        return $this->peso_paquete;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getReservado() {
        return $this->reservado;
    }

    public function getBaja() {
        return $this->baja;
    }

    public function getErrores() {
        return $this->errores;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setAlimento_codigo($alimento_codigo) {
        $this->alimento_codigo = $alimento_codigo;
    }

    public function setFecha_vencimiento($fecha_vencimiento) {
        $this->fecha_vencimiento = $fecha_vencimiento;
    }

    public function setContenido($contenido) {
        $this->contenido = $contenido;
    }

    public function setPeso_paquete($peso_paquete) {
        $this->peso_paquete = $peso_paquete;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function setReservado($reservado) {
        $this->reservado = $reservado;
    }

    public function setBaja($baja) {
        $this->baja = $baja;
    }

    public function setErrores($errores) {
        $this->errores = $errores;
    }

    public function setError($clave, $error) {
        $this->errores[$clave] = $error;
    }

}

?>