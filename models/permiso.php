<?php

class Permiso extends Model {

    public function __construct() {
        parent::__construct();
    }

    public static function obtenerPermisosDe($id_usuario) {
        $sql = "select nombre_permiso
                from usuario u 
                inner join usuario_grupo ug  on (u.id = ug.usuario_id)
                inner join grupo g on (g.id = ug.grupo_id)
                inner join grupo_permiso gp  on (g.id= gp.grupo_id)
                inner join permiso p on (p.id= gp.permiso_id)
                where (u.id = $id_usuario)";
        $query = self::nuevaDb()->ejecutar($sql);
        $permisos = $query->fetchAll(PDO::FETCH_COLUMN);
        return $permisos;
    }
    
        public static function obtenerGrupos() {
        $sql = "select nombre_grupo, level from grupo";
        $query = self::nuevaDb()->ejecutar($sql);
        $permisos = $query->fetchAll(PDO::FETCH_KEY_PAIR);
        return $permisos;
    }    

    public static function obtenerRol($id_usuario) {
        $sql = "select nombre_grupo
                from usuario u 
                inner join usuario_grupo ug  on (u.id = ug.usuario_id)
                inner join grupo g on (g.id = ug.grupo_id)
                where (u.id = $id_usuario)";
        $query = self::nuevaDb()->ejecutar($sql);
        $permisos = $query->fetch(PDO::FETCH_COLUMN);
        return $permisos;
    }    

}

?>
