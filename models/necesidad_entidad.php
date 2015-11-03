<?php

class NecesidadEntidad extends Model {
    
    private $id;
    private $descripcion;
    private $baja;
    private $errores = array();
    
    public static function nuevo($params, $id = null) {
        $necesidad = new self();
        $necesidad->setId($id);
        $necesidad->setDescripcion($params['descripcion']);       
        return $necesidad;
    }
    
    public static function inicializar($params) {
        $necesidad = new self();
        $necesidad->setId($params['id']);
        $necesidad->setDescripcion($params['descripcion']);        
        $necesidad->setBaja($params['baja']);
        return $necesidad;
    }
    
    public function guardar() {
        $sql = 'INSERT INTO `necesidad_entidad`(`descripcion`) VALUES (UPPER(:descripcion))';
        $query = $this->getDb()->prepare($sql);
        $query->bindParam(':descripcion', $this->getDescripcion());
        return $query->execute();
    }
    
    public function borrar() {
        $sql = 'UPDATE necesidad_entidad SET baja = 1 WHERE (id = :id )';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":id" => $this->getId()));
    }
    
    public function actualizar() {
        $sql = 'UPDATE necesidad_entidad SET descripcion = :descripcion'
              .' WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":descripcion" => $this->getDescripcion(),                                     
                                     ":id" => $this->getId()));
    }
    
     public static function getById($id) {
        $sql = 'select * from necesidad_entidad where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC); 
        $result = NecesidadEntidad::inicializar($arreglo[0]);
        return $result;
    }
    
    public static function obtenerTodos() {
        $sql = 'select * from necesidad_entidad';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function buscarPor($atributo, $valor) {
        $sql = "select * from necesidad_entidad where $atributo = :valor ;";
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

    public function setId($id) {
        $this->id = $id;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getBaja() {
        return $this->baja;
    }

    public function getErrores() {
        return $this->errores;
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

