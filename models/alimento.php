<?php

class Alimento extends Model {

    private $id;
    private $codigo;
    private $descripcion;
    private $baja;
    private $errores = array();

    /* para altas ya que no se necesitan todos los atributos, el alimento aun no existe en la bd */

    public static function nuevo($params, $id = null) {
        $alimento = new self();
        $codigo = str_replace(" ", "_", $params['codigo']);
        $alimento->setCodigo($codigo);
        $alimento->setDescripcion($params['descripcion']);
        $alimento->setId($id);
        return $alimento;
    }

    /* dado un arreglo de 4 posiciones, devuelve un objeto alimento instanciado y completo */

    public static function inicializar($params) {
        $alimento = new self();
        $alimento->setId($params['id']);
        $codigo = str_replace(" ", "_", $params['codigo']);
        $alimento->setCodigo($codigo);
        $alimento->setBaja($params['baja']);
        $alimento->setDescripcion($params['descripcion']);
        return $alimento;
    }

    /* guarda el objeto alimento nuevo en la base de datos */

    public function guardar() {
        $sql = 'INSERT INTO `alimento`(`codigo`, `descripcion`) VALUES (UPPER(:codigo), :descripcion)';
        $query = $this->getDb()->prepare($sql);
        $query->bindParam(':codigo', $this->getCodigo());
        $query->bindParam(':descripcion', $this->getDescripcion());
        return $query->execute();
    }

    /* realiza la baja logica de un objeto alimento */

    public function borrar() {
        $sql = 'UPDATE alimento SET baja = 1 WHERE (id = :id )';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":id" => $this->getId()));
    }

    /* plazma en la base de datos el estado actual del objeto alimento */

    public function actualizar() {
        $sql = 'UPDATE alimento '
                . 'SET codigo = :codigo, descripcion = :descripcion '
                . 'WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":codigo" => $this->getCodigo(),
                    ":descripcion" => $this->getDescripcion(),
                    ":id" => $this->getId()));
    }

    /* devulve un objeto alimento especifico dado un id */

    public static function getById($id) {
        $sql = 'select * from alimento where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = Alimento::inicializar($arreglo[0]);
        return $result;
    }

    /* devuleve un arreglo de alimentos */

    public static function obtenerTodos() {
        $sql = 'select * from alimento';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* buscador generico de todos los elementos que en determinado atributo tengan cierto valor. */

    public static function buscarPor($atributo, $valor) {
        $sql = "select * from alimento where $atributo = :valor ;";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getStockOfall() {
        $sql = "select alimento.id as id, codigo, sum(stock) as stock_total, descripcion, alimento.baja
                from alimento left join detalle_alimento on(alimento.codigo = detalle_alimento.alimento_codigo)
                group by codigo";
        $query = self::nuevaDb()->ejecutar($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getId() {
        return $this->id;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getErrores() {
        return $this->errores;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setErrores($errores) {
        $this->errores = $errores;
    }

    public function setError($clave, $error) {
        $this->errores[$clave] = $error;
    }

    public function getBaja() {
        return $this->baja;
    }

    public function setBaja($baja) {
        $this->baja = $baja;
    }

}

?>