<?php
require_once __DIR__ . '/../config/conexion.php';

class Usuario {
    private $conexion;
    private $tabla = "usuarios";
    
    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }
    
    public function validarLogin($nombre_usuario, $clave) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE nombre_usuario = :usuario AND activo = 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":usuario", $nombre_usuario);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($clave, $usuario['clave_hash'])) {
                return $usuario;
            }
        }
        return false;
    }
    
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->tabla . " ORDER BY nombre_usuario";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function crear($nombre_usuario, $clave, $rol) {
        $query = "INSERT INTO " . $this->tabla . " (nombre_usuario, clave_hash, rol) VALUES (:usuario, :clave, :rol)";
        $stmt = $this->conexion->prepare($query);
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
        $stmt->bindParam(":usuario", $nombre_usuario);
        $stmt->bindParam(":clave", $clave_hash);
        $stmt->bindParam(":rol", $rol);
        return $stmt->execute();
    }
}
?>
