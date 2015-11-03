<?php

class Donacion extends Model {

    private $id;
    private $detalle_id;
    private $donante_id;
    private $cantidad;
    private $baja;
    private $errores = array();

    /* para altas ya que no se necesitan todos los atributos, el alimento aun no existe en la bd */

    public static function nuevo($params, $id = null) {
        $alimento = new self();
        $alimento->setDetalle_id($params['detalle_id']);
        $alimento->setDonante_id($params['donante_id']);
        $alimento->setCantidad($params['stock']);
        $alimento->setId($id);
        return $alimento;
    }

    /* dado un arreglo de 4 posiciones, devuelve un objeto alimento instanciado y completo */

    public static function inicializar($params) {
        $alimento = new self();
        $alimento->setId($params['id']);
        $alimento->setDetalle_id($params['detalle_id']);
        $alimento->setDonante_id($params['donante_id']);
        $alimento->setCantidad($params['cantidad']);
        $alimento->setBaja($params['baja']);
        return $alimento;
    }

    /*guarda el objeto alimento nuevo en la base de datos*/

    public function guardar() {
        $sql = 'INSERT INTO `alimento_donante` (`detalle_alimento_id`, `donante_id`, `cantidad`) VALUES (:detalle_alimento_id, :donante_id, :cantidad)';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(':detalle_alimento_id' => $this->getDetalle_id(),
                                     ':donante_id' => $this->getDonante_id(),
                                     ':cantidad' => $this->getCantidad()
                                    ));
    }

    /*realiza la baja logica de un objeto alimento*/

    public function borrar() {
        $sql = 'UPDATE alimento_donante SET baja = 1 WHERE (id = :id )';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":id" => $this->getId()));
    }

    /*plazma en la base de datos el estado actual del objeto alimento*/

    public function actualizar() {
        $sql = 'UPDATE alimento_donante '
                . 'SET detalle_id = :detalle_id, donante_id = :donante_id '
                . 'WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(':detalle_id' => $this->getDetalle_id(),
                                     ':descripcion' => $this->getdonante_id(),
                                     ":id" => $this->getId()));
    }

    /*devulve un objeto alimento especifico dado un id*/

    public static function getById($id) {
        $sql = 'select * from alimento_donante where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = Alimento::inicializar($arreglo[0]);
        return $result;
    }

    /*devuleve un arreglo de alimentos*/

    public static function obtenerTodos() {
        $sql = 'select * from alimento';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*buscador generico de todos los elementos que en determinado atributo tengan cierto valor.*/

    public static function buscarPor($atributo, $valor) {
        $sql = "select * from alimento_donante where $atributo = :valor";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getId() {
        return $this->id;
    }

    public function getDetalle_id() {
        return $this->detalle_id;
    }

    public function getDonante_id() {
        return $this->donante_id;
    }

    public function getCantidad() {
        return $this->cantidad;
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

    public function setDetalle_id($detalle_id) {
        $this->detalle_id = $detalle_id;
    }

    public function setDonante_id($donante_id) {
        $this->donante_id = $donante_id;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function setBaja($baja) {
        $this->baja = $baja;
    }

    public function setErrores($errores) {
        $this->errores = $errores;
    }
    
      public function setError($clave,$error) {
        $this->errores[$clave] = $error;
    }


}

?>