<?php
require_once __DIR__ . '/../config/conexion.php';

class Incidencia {
    private $conexion;
    private $tabla = "incidencias";
    
    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }
    
    public function obtenerTodas() {
        $query = "SELECT i.*, e.nombre_completo, e.cedula 
                  FROM " . $this->tabla . " i 
                  INNER JOIN empleados e ON i.id_empleado = e.id_empleado 
                  ORDER BY i.fecha_inicio DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function crear($id_empleado, $tipo_incidencia, $fecha_inicio, $fecha_fin, $descripcion) {
        $query = "INSERT INTO " . $this->tabla . " (id_empleado, tipo_incidencia, fecha_inicio, fecha_fin, descripcion) 
                  VALUES (:id_empleado, :tipo, :fecha_inicio, :fecha_fin, :descripcion)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":tipo", $tipo_incidencia);
        $stmt->bindParam(":fecha_inicio", $fecha_inicio);
        $stmt->bindParam(":fecha_fin", $fecha_fin);
        $stmt->bindParam(":descripcion", $descripcion);
        return $stmt->execute();
    }
    
    public function actualizar($id, $tipo_incidencia, $fecha_inicio, $fecha_fin, $descripcion) {
        $query = "UPDATE " . $this->tabla . " SET tipo_incidencia = :tipo, fecha_inicio = :fecha_inicio, 
                  fecha_fin = :fecha_fin, descripcion = :descripcion WHERE id_incidencia = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":tipo", $tipo_incidencia);
        $stmt->bindParam(":fecha_inicio", $fecha_inicio);
        $stmt->bindParam(":fecha_fin", $fecha_fin);
        $stmt->bindParam(":descripcion", $descripcion);
        return $stmt->execute();
    }
    
    public function eliminar($id) {
        $query = "DELETE FROM " . $this->tabla . " WHERE id_incidencia = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    
    public function obtenerPorRangoFechas($fecha_inicio, $fecha_fin, $tipo = null) {
        if ($tipo) {
            $query = "SELECT i.*, e.nombre_completo, e.cedula 
                      FROM " . $this->tabla . " i 
                      INNER JOIN empleados e ON i.id_empleado = e.id_empleado 
                      WHERE i.fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin 
                      AND i.tipo_incidencia = :tipo
                      ORDER BY i.fecha_inicio DESC";
        } else {
            $query = "SELECT i.*, e.nombre_completo, e.cedula 
                      FROM " . $this->tabla . " i 
                      INNER JOIN empleados e ON i.id_empleado = e.id_empleado 
                      WHERE i.fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin 
                      ORDER BY i.fecha_inicio DESC";
        }
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":fecha_inicio", $fecha_inicio);
        $stmt->bindParam(":fecha_fin", $fecha_fin);
        if ($tipo) {
            $stmt->bindParam(":tipo", $tipo);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
