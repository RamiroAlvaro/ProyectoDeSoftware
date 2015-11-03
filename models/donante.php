<?php

class Donante extends Model {

    private $id;
    private $razon_social;
    private $apellido_contacto;
    private $nombre_contacto;
    private $telefono_contacto;
    private $mail_contacto;
    private $domicilio_contacto;
    private $baja;
    private $errores = array();

 

    public static function nuevo($params, $id = null) {
        $donante = new self();
        $donante->setRazonSocial($params['razon_social']);
        $donante->setApellidoContacto($params['apellido_contacto']);
        $donante->setNombreContacto($params['nombre_contacto']);
        $donante->setTelefonoContacto($params['telefono_contacto']);
        $donante->setMailContacto($params['mail_contacto']);
        $donante->setDomicilioContacto($params['domicilio_contacto']);
        $donante->setId($id);
        return $donante;
    }

    /* dado un arreglo de 8 posiciones, devuelve un objeto donante instanciado y completo */

    public static function inicializar($params) {
        $donante = new self();
        $donante->setId($params['id']);
        $donante->setRazonSocial($params['razon_social']);
        $donante->setApellidoContacto($params['apellido_contacto']);
        $donante->setNombreContacto($params['nombre_contacto']);
        $donante->setTelefonoContacto($params['telefono_contacto']);
        $donante->setMailContacto($params['mail_contacto']);
        $donante->setDomicilioContacto($params['domicilio_contacto']);
        $donante->setBaja($params['baja']);
        return $donante;
    }
        
 

    /*guarda el objeto donante nuevo en la base de datos*/

    public function guardar() {
        $sql = 'INSERT INTO `donante`(`razon_social`, `apellido_contacto`, `nombre_contacto`, `telefono_contacto`, `mail_contacto`, `domicilio_contacto`) VALUES (:razon_social, :apellido_contacto, :nombre_contacto, :telefono_contacto, :mail_contacto, :domicilio_contacto)';
        $query = $this->getDb()->prepare($sql);
        $query->bindParam(':razon_social', $this->getRazonSocial());
        $query->bindParam(':apellido_contacto', $this->getApellidoContacto());
        $query->bindParam(':nombre_contacto', $this->getNombreContacto());
        $query->bindParam(':telefono_contacto', $this->getTelefonoContacto());
        $query->bindParam(':mail_contacto', $this->getMailContacto());
        $query->bindParam(':domicilio_contacto', $this->getDomicilioContacto());
        return $query->execute();
    }

    /*realiza la baja logica de un objeto donante*/

    public function borrar() {
        $sql = 'UPDATE donante SET baja = 1 WHERE (id = :id )';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":id" => $this->getId()));
    }

    /*plazma en la base de datos el estado actual del objeto donante*/

    public function actualizar() {
        $sql = 'UPDATE donante '
                . 'SET razon_social = :razon_social, apellido_contacto = :apellido_contacto,'
                . ' nombre_contacto = :nombre_contacto, telefono_contacto = :telefono_contacto,'
                . ' mail_contacto = :mail_contacto, domicilio_contacto = :domicilio_contacto'
                . ' WHERE id = :id';
        $query = $this->getDb()->prepare($sql);
        return $query->execute(array(":razon_social" => $this->getRazonSocial(),
                                     ":apellido_contacto" => $this->getApellidoContacto(),
                                     ":nombre_contacto" => $this->getNombreContacto(),
                                     ":telefono_contacto" => $this->getTelefonoContacto(),
                                     ":mail_contacto" => $this->getMailContacto(),
                                     ":domicilio_contacto" => $this->getDomicilioContacto(),
                                     ":id" => $this->getId()));
    }

    /*devulve un objeto donante especifico dado un id*/

    public static function getById($id) {
        $sql = 'select * from donante where id = :id';
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":id" => $id));
        $arreglo = $query->fetchAll(PDO::FETCH_ASSOC); 
        $result = Donante::inicializar($arreglo[0]);
        return $result;
    }

    /*devuleve un arreglo de donantes*/

    public static function obtenerTodos() {
        $sql = 'select * from donante';
        $query = self::nuevaDb()->ejecutar($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*buscador generico de todos los elementos que en determinado atributo tengan cierto valor.*/

    public static function buscarPor($atributo, $valor) {
        $sql = "select * from donante where $atributo = :valor ;";
        $query = self::nuevaDb()->prepare($sql);
        $query->execute(array(":valor" => $valor));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getId() {
        return $this->id;
    }

    public function getRazonSocial() {
        return $this->razon_social;
    }

    public function getApellidoContacto() {
        return $this->apellido_contacto;
    }
    
    public function getNombreContacto(){
        return $this->nombre_contacto;
    }
    
    public function getTelefonoContacto(){
        return $this->telefono_contacto;
    }
    
    public function getMailContacto() {
        return $this->mail_contacto;
    }
    
    public function getDomicilioContacto() {
        return $this->domicilio_contacto;
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

    public function setRazonSocial($razon_social) {
        $this->razon_social = $razon_social;
    }
    
    public function setApellidoContacto($apellido_contacto) {
        $this->apellido_contacto = $apellido_contacto;
    }

    public function setNombreContacto($nombre_contacto) {
        $this->nombre_contacto = $nombre_contacto;
    }
    
    public function setTelefonoContacto($telefono_contacto) {
        $this->telefono_contacto = $telefono_contacto;
    }
    
    public function setMailContacto($mail_contacto) {
        $this->mail_contacto = $mail_contacto;
    }
    
    public function setDomicilioContacto($domicilio_contacto) {
        $this->domicilio_contacto = $domicilio_contacto;
    }

    public function setErrores($errores) {
        $this->errores = $errores;
    }
    
    public function setError($clave,$error) {
        $this->errores[$clave] = $error;
    }



    public function setBaja($baja) {
        $this->baja = $baja;
    }

}

?>

