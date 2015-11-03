<?php

Class EstadoEntidad extends Model {
    
    private $id;
    private $descripcion;
    private $baja;
    private $errores = array();
    
    public static function nuevo($params, $id = null) {
        $estado = new self();
        $estado->setId($id);
        $estado->setDescripcion($params['descripcion']);       
        return $estado;
    }
    
    public static function inicializar($params) {
        $estado = new self();
        $estado->setId($params['id']);
        $estado->setDescripcion($params['descripcion']);        
        $estado->setBaja($params['baja']);
        return $estado;
    }
    
    public function guardar() {
        $sql = 'INSERT INTO `estado_entidad`(`descripcion`) VALUES (UPPER(:descripcion))';
        $query = $this->getDb()->prepare($sql);
        $query->bindParam(':descripcion', $this->getDescripcion());
        return $query->execute();
    }
    
    public function borrar() {
        $sql = 'UPDATE estado_entidad SET baja = 1 WHERE (id = :id )';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":id" => $this->getId()));
    }
    
    public function actualizar() {
        $sql = 'UPDATE estado_entidad SET descripcion = UPPER(:descripcion)'
              .'WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":descripcion" => $this->getDescripcion(),                                     
                                     ":id" => $this->getId()));
    }
    
     public static function getById($id) {
        $sql = 'select * from estado_entidad where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC); 
        $result = EstadoEntidad::inicializar($arreglo[0]);
        return $result;
    }
    
    public static function obtenerTodos() {
        $sql = 'select * from estado_entidad';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function buscarPor($atributo, $valor) {
        $sql = "select * from estado_entidad where $atributo = :valor ;";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function getDescripcion() {
        return $this->descripcion;
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

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
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
