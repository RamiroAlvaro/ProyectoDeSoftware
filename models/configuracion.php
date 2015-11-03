<?php
class Configuracion extends Model {
    
    private $id;
    private $clave;
    private $valor;
    private $baja;
    private $errores = array();
    
    /* para altas ya que no se necesitan todos los atributos, la configuracion aun no existe en la bd */
    
    public static function nuevo ($params, $id = null){
        $configuracion = new self();
        $configuracion->setClave($params['clave']);
        $configuracion->setValor($params['valor']);
        $configuracion->setId($id);
        return $configuracion;
    }
    
    /* dado un arreglo de 4 posiciones, devuelve un objeto configuracion instanciado y completo */
    
    public static function inicializar($params){
        $configuracion = new self();
        $configuracion->setId($params['id']);
        $configuracion->setClave($params['clave']);
        $configuracion->setValor($params['valor']);
        $configuracion->setBaja($params['baja']);
        return $configuracion;
        
    }
    
     /* guarda el objeto configuracion nuevo en la base de datos */

    public function guardar() {
        $sql = 'INSERT INTO `configuracion`(`clave`, `valor`) VALUES (UPPER(:clave), :valor)';
        $query = $this->getDb()->prepare($sql);
        $query->bindParam(':clave', $this->getClave());
        $query->bindParam(':valor', $this->getValor());
        return $query->execute();
    }
    
     /* realiza la baja logica de un objeto configuracion */

    public function borrar() {
        $sql = 'UPDATE configuracion SET baja = 1 WHERE (id = :id )';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":id" => $this->getId()));
    }
    
    /* plazma en la base de datos el estado actual del objeto configuracion */

    public function actualizar() {
        $sql = 'UPDATE configuracion '
                . 'SET clave = :clave, valor = :valor '
                . 'WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":clave" => $this->getClave(),
                    ":valor" => $this->getValor(),
                    ":id" => $this->getId()));
    }
    
     /* devulve un objeto configuracion especifico dado un id */

    public static function getById($id) {
        $sql = 'select * from configuracion where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = Configuracion::inicializar($arreglo[0]);
        return $result;
    }
    
         /* devuelve un objeto configuracion especifico dado una clave */

    public static function getByClave($clave) {
        $sql = 'select * from configuracion where clave = :clave';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":clave" => $clave));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = Configuracion::inicializar($arreglo[0]);
        return $result;
    }
    
    /* devuelve un arreglo de configuraciones */

    public static function obtenerTodos() {
        $sql = 'select * from configuracion';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /* buscador generico de todos los elementos que en determinado atributo tengan cierto valor. */

    public static function buscarPor($atributo, $valor) {
        $sql = "select * from configuracion where $atributo = :valor ;";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    
    
    public function getId(){
        return $this->id;
    }
    
    public function getClave(){
        return $this->clave;
    }
    
    public function getValor(){
        return $this->valor; 
    }
    
    public function getBaja(){
        return $this->baja;
    }
    
    public function getErrores(){
        return $this->errores;
    }
    
    public function setId($id) {
        $this->id=$id;
    }
    
    public function setClave($clave) {
        $this->clave=$clave;
    }
    
    public function setValor($valor) {
        $this->valor=$valor;
    }
    
    public function setBaja($baja) {
        $this->baja=$baja;
    }
    
    public function setErrores($errores) {
        $this->errores=$errores;
    }
    
    public function setError($clave, $error) {
        $this->errores[$clave] = $error;
    }
    
    
    
    
}

?>