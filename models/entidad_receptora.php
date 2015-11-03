<?php

class EntidadReceptora extends Model {
    
    private $id;
    private $razon_social;
    private $telefono;
    private $domicilio;
    private $estado_entidad_id;
    private $necesidad_entidad_id;
    private $servicio_prestado_id;
    private $baja;
    private $errores = array();
    
        /* para altas ya que no se necesitan todos los atributos. La entidad aun no existe en la bd */
    
    public static function nuevo($params, $id=null) {
        $entidad_receptora = new self();
        $entidad_receptora->setRazon_social($params['razon_social']);
        $entidad_receptora->setTelefono($params['telefono']);
        $entidad_receptora->setDomicilio($params['domicilio']);
        $entidad_receptora->setEstado_entidad_id($params['estado_entidad_id']);
        $entidad_receptora->setNecesidad_entidad_id($params['necesidad_entidad_id']);
        $entidad_receptora->setServicio_prestado_id($params['servicio_prestado_id']);
        return $entidad_receptora;        
    }
    
        /* Devuelve un objeto entidad receptora instanciado y completo */

    
    public static function inicializar($params) {
        $entidad_receptora = new self();
        $entidad_receptora->setId($params['id']);
        $entidad_receptora->setRazon_social($params['razon_social']);
        $entidad_receptora->setTelefono($params['telefono']);
        $entidad_receptora->setDomicilio($params['domicilio']);
        $entidad_receptora->setEstado_entidad_id($params['estado_entidad_id']);
        $entidad_receptora->setNecesidad_entidad_id($params['necesidad_entidad_id']);
        $entidad_receptora->setServicio_prestado_id($params['servicio_prestado_id']);
        $entidad_receptora->setBaja($params['baja']);
        return $entidad_receptora;      
    }
    
    /*   Guarda el objeto entidad nuevo en la bd   */
    
    public function guardar() {
        $sql = 'INSERT INTO entidad_receptora (razon_social, telefono, domicilio, '
                . 'estado_entidad_id, necesidad_entidad_id, servicio_prestado_id)'
                . 'VALUES (UPPER(:razon_social), :telefono, UPPER(:domicilio), '
                . ':estado_entidad_id, :necesidad_entidad_id, :servicio_prestado_id)';
        $query = $this->getDb()->prepare($sql);
        $query->bindParam(':razon_social', $this->getRazon_social());
        $query->bindParam(':telefono', $this->getTelefono());
        $query->bindParam(':domicilio', $this->getDomicilio());
        $query->bindParam(':estado_entidad_id', $this->getEstado_entidad_id());
        $query->bindParam(':necesidad_entidad_id', $this->getNecesidad_entidad_id());
        $query->bindParam(':servicio_prestado_id', $this->getServicio_prestado_id());
        return $query->execute();
    }
    
    /* Baja logica de una entidad receptora */
    
    public function borrar() {
        $sql = 'UPDATE entidad_receptora SET baja = 1 WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        $query->bindParam(':id', $this->getId());
        return $query->execute();
    }
    
    /* Actualiza la bd */
    
    public function actualizar() {
        $sql = 'UPDATE entidad_receptora SET razon_social = UPPER(:razon_social),'
                . 'telefono = :telefono, domicilio = UPPER(:domicilio), '
                . 'estado_entidad_id = :estado_entidad_id,'
                . 'necesidad_entidad_id = :necesidad_entidad_id,'
                . 'servicio_prestado_id = :servicio_prestado_id'
                . ' WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        $query->bindParam(':id', $this->getId());
        $query->bindParam(':razon_social', $this->getRazon_social());
        $query->bindParam(':telefono', $this->getTelefono());
        $query->bindParam(':domicilio', $this->getDomicilio());
        $query->bindParam(':estado_entidad_id', $this->getEstado_entidad_id());
        $query->bindParam(':necesidad_entidad_id', $this->getNecesidad_entidad_id());
        $query->bindParam(':servicio_prestado_id', $this->getServicio_prestado_id());
        return $query->execute();
        
    }
    
        /*devulve un objeto entidad dado un id*/
    
    public static function getById($id) {
        $sql = 'select * from entidad_receptora where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = EntidadReceptora::inicializar($arreglo[0]);
        return $result;
    }
    
    /* devuelve un arreglo de entidades receptoras */
    
    public static function obtenerTodos() {
        $sql = 'SELECT er.id, razon_social, telefono, domicilio, ee.descripcion AS estado, 
                er.baja, ne.descripcion AS necesidad, sp.descripcion AS servicio
                FROM entidad_receptora er
                INNER JOIN estado_entidad ee ON er.estado_entidad_id = ee.id
                INNER JOIN necesidad_entidad ne ON er.necesidad_entidad_id = ne.id
                INNER JOIN servicio_prestado sp ON er.servicio_prestado_id = sp.id';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*buscador generico de todos los elementos que en determinado atributo tengan cierto valor.*/

    
    public static function buscarPor($atributo, $valor) {
        $sql = "select * from entidad_receptora where $atributo = :valor ;";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
        
    public function getId() {
        return $this->id;
    }

    public function getRazon_social() {
        return $this->razon_social;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getDomicilio() {
        return $this->domicilio;
    }

    public function getEstado_entidad_id() {
        return $this->estado_entidad_id;
    }

    public function getNecesidad_entidad_id() {
        return $this->necesidad_entidad_id;
    }

    public function getServicio_prestado_id() {
        return $this->servicio_prestado_id;
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

    public function setRazon_social($razon_social) {
        $this->razon_social = $razon_social;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setDomicilio($domicilio) {
        $this->domicilio = $domicilio;
    }

    public function setEstado_entidad_id($estado_entidad_id) {
        $this->estado_entidad_id = $estado_entidad_id;
    }

    public function setNecesidad_entidad_id($necesidad_entidad_id) {
        $this->necesidad_entidad_id = $necesidad_entidad_id;
    }

    public function setServicio_prestado_id($servicio_prestado_id) {
        $this->servicio_prestado_id = $servicio_prestado_id;
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
