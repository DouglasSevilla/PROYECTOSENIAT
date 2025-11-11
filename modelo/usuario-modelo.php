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
        $ok = $stmt->execute();
        if (!$ok) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al ejecutar SELECT usuario (login): ' . implode(' | ', $err));
        }
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
        $ok = $stmt->execute();
        if (!$ok) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al ejecutar SELECT usuarios: ' . implode(' | ', $err));
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id", $id);
        $ok = $stmt->execute();
        if (!$ok) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al ejecutar SELECT usuario por id: ' . implode(' | ', $err));
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorNombre($nombre_usuario) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE nombre_usuario = :usuario";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":usuario", $nombre_usuario);
        $ok = $stmt->execute();
        if (!$ok) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al ejecutar SELECT usuario por nombre: ' . implode(' | ', $err));
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear($nombre_usuario, $clave, $rol) {
        $query = "INSERT INTO " . $this->tabla . " (nombre_usuario, clave_hash, rol) VALUES (:usuario, :clave, :rol)";
        $stmt = $this->conexion->prepare($query);
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
        $stmt->bindParam(":usuario", $nombre_usuario);
        $stmt->bindParam(":clave", $clave_hash);
        $stmt->bindParam(":rol", $rol);
        $ok = $stmt->execute();
        if (!$ok) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al ejecutar INSERT usuario: ' . implode(' | ', $err));
        }
        return $ok;
    }
    
    public function actualizar($id, $nombre_usuario, $rol) {
        $query = "UPDATE " . $this->tabla . " SET nombre_usuario = :usuario, rol = :rol WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":usuario", $nombre_usuario);
        $stmt->bindParam(":rol", $rol);
        $ok = $stmt->execute();
        if (!$ok) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al ejecutar UPDATE usuario: ' . implode(' | ', $err));
        }
        return $ok;
    }
    
    public function cambiarClave($id, $clave_nueva) {
        $query = "UPDATE " . $this->tabla . " SET clave_hash = :clave WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($query);
        $clave_hash = password_hash($clave_nueva, PASSWORD_DEFAULT);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":clave", $clave_hash);
        $ok = $stmt->execute();
        if (!$ok) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al ejecutar UPDATE clave usuario: ' . implode(' | ', $err));
        }
        return $ok;
    }
    
    public function eliminar($id) {
        $query = "UPDATE " . $this->tabla . " SET activo = 0 WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id", $id);
        $ok = $stmt->execute();
        if (!$ok) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al ejecutar DELETE(logical) usuario: ' . implode(' | ', $err));
        }
        return $ok;
    }
}
?>

