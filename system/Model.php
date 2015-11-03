<?php

class Model {
    static protected $db;
    
    function __construct(){
        Model::$db = Model::nuevaDb();
    }
    public function getDb() {
        return Model::$db;
    }
    
    public static function lastInsertId() {
        return Model::$db->lastInsertId();
    }
    
    protected static function nuevaDb() {
        include_once SYS_PATH . 'DataBase.php';
        if(!isset(Model::$db))
            Model::$db = new DataBase();
        return Model::$db;
    }
    
    public function ultimoId(){
        return $this->db->lastInsertId();
        
    }
    

}
