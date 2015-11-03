<?php

class DataBase {

    private $conexion;

    public function __construct() {
        try {
          $db = new PDO("mysql:host=localhost;dbname=grupo_13", "grupo_13", "WMNfprH4JilOFjVW");
            $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
            $this->conexion = $db;
        } catch (PDOException $e) {
            print '<p>ErrorPDO: </p>' . $e->getMessage();
            exit();
        }
    }
    
    public function getPDO() {
        return $this->conexion;
    }
    
    public function prepare($string){
        return $this->conexion->prepare($string);
    }
    
    public function ejecutar($sql){
        return $this->conexion->query($sql);
    }
    
    public function convertir_arreglo($datos){
        return $datos->fetch(PDO::FETCH_ASSOC);
    }
    public function cant_filas($datos){
        return $datos->rowCount();
    }

    public function lastInsertId(){
        return $this->conexion->lastInsertId();
    }
}

?>
