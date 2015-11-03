<?php

class Usuario extends Model {

    private $id;
    private $username;
    private $clave;
    private $reclave;
    private $baja;
    private $rol;
    private $errores = array();

    /* para altas ya que no se necesitan todos los atributos, el usuario aun no existe en la bd */

    public static function nuevo($params, $id = null) {
        $usuario = new self();
        $usuario->setUsername($params['username']);
        $usuario->setClave(md5($params['pass']));
        $usuario->setReclave(md5($params['repass']));
        $usuario->setRol($params['rol']);
        $usuario->setId($id);
        return $usuario;
    }

    /* dado un arreglo de 4 posiciones, devuelve un objeto usuario instanciado y completo */

    public static function inicializar($params) {
        $usuario = new self();
        $usuario->setId($params['id']);
        $usuario->setUsername($params['username']);
        $usuario->setClave($params['clave']);
        $usuario->setRol($params['rol']);
        return $usuario;
    }

    /* guarda el objeto usuario nuevo en la base de datos */

    public function guardar() {
        $sql = "select nombre_grupo from grupo where id = :id";
        $query = $this->getDb()->prepare($sql);
        $query->execute(array(':id' => $this->getRol()));
        $rol_name = $query->fetch(PDO::FETCH_ASSOC);
        $sql = 'INSERT INTO `usuario`(`username`, `clave`, `rol`) VALUES (:username, :clave, :rol)';
        $query = $this->getDb()->prepare($sql);
        return $exito = $query->execute(array(":username" => $this->getUsername(),
                                     ":clave" => $this->getClave(),
                                     ":rol" => $rol_name['nombre_grupo']));
    }
    public function asociar(){
        $sql = 'INSERT INTO `usuario_grupo`(`usuario_id`, `grupo_id`) VALUES (:usuario_id, :grupo_id)';
        $query = $this->getDb()->prepare($sql);
        $this->setId($this->getDb()->lastInsertId());
        return $query->execute(array(":usuario_id" => $this->getId(),
                                     ":grupo_id" => $this->getRol()));
    }
    public function reasociar(){
        $sql = 'UPDATE usuario_grupo '
                . 'set grupo_id = :grupo_id '
                . 'where usuario_id = :usuario_id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":usuario_id" => $this->getId(),
                                     ":grupo_id" => $this->getRol()));
    }
    /* realiza la baja logica de un objeto usuario */

    public function borrar() {
        $sql = 'UPDATE usuario SET baja = 1 WHERE (id = :id )';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":id" => $this->getId()));
    }

    /* plazma en la base de datos el estado actual del objeto usuario */

    public function actualizar() {
        $sql = "select nombre_grupo from grupo where id = :id";
        $query = $this->getDb()->prepare($sql);
        $query->execute(array(':id' => $this->getRol()));
        $rol_name = $query->fetch(PDO::FETCH_ASSOC);
        $sql = 'UPDATE usuario '
                . 'SET username = :username, clave = :clave, rol = :rol '
                . 'WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":username" => $this->getUsername(),
                                     ":clave" => $this->getClave(),
                                     ":rol" => $rol_name['nombre_grupo'],
                                     ":id" => $this->getId()));
    }

    /* devulve un objeto usuario especifico dado un id */

    public static function getById($id) {
        $sql = 'select * from usuario where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = Usuario::inicializar($arreglo[0]);
        return $result;
    }

    /* devuleve un arreglo de usuarios */

    public static function obtenerTodos() {
        $sql = 'select * from usuario';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* buscador generico de todos los elementos que en determinado atributo tengan cierto valor. */

    public static function buscarPor($atributo, $valor) {
        $sql = "select * from usuario where $atributo = :valor ;";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    static function obtener_roles(){
        $sql = 'select id, nombre_grupo as nombre from grupo';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getDonations() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getClave() {
        return $this->clave;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setClave($clave) {
        $this->clave = $clave;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }

    public function getErrores() {
        return $this->errores;
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
    public function getReclave() {
        return $this->reclave;
    }

    public function setReclave($reclave) {
        $this->reclave = $reclave;
    }


}

?>