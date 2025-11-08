<?php
require_once __DIR__ . '/../config/conexion.php';

class HistorialOperacion {
    private $conexion;
    private $tabla = "historial_operaciones";
    
    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }
    
    public function registrar($id_usuario, $accion, $tabla_afectada = null, $id_registro_afectado = null) {
        $query = "INSERT INTO " . $this->tabla . " (id_usuario, accion, tabla_afectada, id_registro_afectado) 
                  VALUES (:id_usuario, :accion, :tabla, :id_registro)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":accion", $accion);
        $stmt->bindParam(":tabla", $tabla_afectada);
        $stmt->bindParam(":id_registro", $id_registro_afectado);
        return $stmt->execute();
    }
    
    public function obtenerTodas($limite = 100) {
        $query = "SELECT h.*, u.nombre_usuario 
                  FROM " . $this->tabla . " h 
                  INNER JOIN usuarios u ON h.id_usuario = u.id_usuario 
                  ORDER BY h.fecha_hora DESC 
                  LIMIT :limite";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorUsuario($id_usuario, $limite = 50) {
        $query = "SELECT h.*, u.nombre_usuario 
                  FROM " . $this->tabla . " h 
                  INNER JOIN usuarios u ON h.id_usuario = u.id_usuario 
                  WHERE h.id_usuario = :id_usuario 
                  ORDER BY h.fecha_hora DESC 
                  LIMIT :limite";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorRangoFechas($fecha_inicio, $fecha_fin, $limite = 200) {
        $query = "SELECT h.*, u.nombre_usuario 
                  FROM " . $this->tabla . " h 
                  INNER JOIN usuarios u ON h.id_usuario = u.id_usuario 
                  WHERE DATE(h.fecha_hora) BETWEEN :fecha_inicio AND :fecha_fin 
                  ORDER BY h.fecha_hora DESC 
                  LIMIT :limite";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":fecha_inicio", $fecha_inicio);
        $stmt->bindParam(":fecha_fin", $fecha_fin);
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorFiltros($fecha_inicio, $fecha_fin, $usuario = null, $tabla = null, $limite = 200) {
        $query = "SELECT h.*, u.nombre_usuario 
                  FROM " . $this->tabla . " h 
                  INNER JOIN usuarios u ON h.id_usuario = u.id_usuario 
                  WHERE DATE(h.fecha_hora) BETWEEN :fecha_inicio AND :fecha_fin";
        
        if ($usuario) {
            $query .= " AND h.id_usuario = :usuario";
        }
        if ($tabla) {
            $query .= " AND h.tabla_afectada = :tabla";
        }
        
        $query .= " ORDER BY h.fecha_hora DESC LIMIT :limite";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":fecha_inicio", $fecha_inicio);
        $stmt->bindParam(":fecha_fin", $fecha_fin);
        if ($usuario) {
            $stmt->bindParam(":usuario", $usuario);
        }
        if ($tabla) {
            $stmt->bindParam(":tabla", $tabla);
        }
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
