<?php

class ServicioPrestado extends Model {
    
    private $id;
    private $descripcion;
    private $baja;
    private $errores = array();  
    
    
    public static function nuevo($params, $id = null) {
        $servicio = new self();
        $servicio->setId($id);
        $servicio->setDescripcion($params['descripcion']);       
        return $servicio;
    }
    
    public static function inicializar($params) {
        $servicio = new self();
        $servicio->setId($params['id']);
        $servicio->setDescripcion($params['descripcion']);        
        $servicio->setBaja($params['baja']);
        return $servicio;
    }
    
    public function guardar() {
        $sql = 'INSERT INTO `servicio_prestado`(`descripcion`) VALUES (UPPER(:descripcion))';
        $query = $this->getDb()->prepare($sql);
        $query->bindParam(':descripcion', $this->getDescripcion());
        return $query->execute();
    }
    
    public function borrar() {
        $sql = 'UPDATE servicio_prestado SET baja = 1 WHERE (id = :id )';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":id" => $this->getId()));
    }
    
    public function actualizar() {
        $sql = 'UPDATE servicio_prestado SET descripcion = UPPER(:descripcion)'
              .'WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":descripcion" => $this->getDescripcion(),                                     
                                     ":id" => $this->getId()));
    }
    
     public static function getById($id) {
        $sql = 'select * from servicio_prestado where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC); 
        $result = ServicioPrestado::inicializar($arreglo[0]);
        return $result;
    }
    
    public static function obtenerTodos() {
        $sql = 'select * from servicio_prestado';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function buscarPor($atributo, $valor) {
        $sql = "select * from servicio_prestado where $atributo = :valor ;";
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


