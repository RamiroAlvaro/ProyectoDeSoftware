<?php

class Login extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getUsuario($username, $clave) {
        $sql = 'select * from usuario where username = :username and clave = :clave';
        $query = self::nuevaDb()->prepare($sql);
        $pass = md5($clave);
        $query->execute(array(":username" => $username, ":clave" => $pass));
        $usuario = $query->fetch(PDO::FETCH_ASSOC);
        return $usuario;
    }

}

?>
