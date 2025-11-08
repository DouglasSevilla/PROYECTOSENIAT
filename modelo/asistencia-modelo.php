<?php
require_once __DIR__ . '/../config/conexion.php';

class Asistencia {
    private $conexion;
    private $tabla = "asistencia";
    
    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }
    
    public function registrarEntrada($id_empleado, $observacion = null) {
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        
        $query = "INSERT INTO " . $this->tabla . " (id_empleado, fecha, hora_entrada, observacion) 
                  VALUES (:id_empleado, :fecha, :hora, :observacion)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":hora", $hora);
        $stmt->bindParam(":observacion", $observacion);
        return $stmt->execute();
    }
    
    public function registrarSalida($id_empleado, $observacion = null) {
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        
        $query = "UPDATE " . $this->tabla . " SET hora_salida = :hora, observacion = :observacion 
                  WHERE id_empleado = :id_empleado AND fecha = :fecha AND hora_salida IS NULL";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":hora", $hora);
        $stmt->bindParam(":observacion", $observacion);
        return $stmt->execute();
    }
    
    public function obtenerPorFecha($fecha) {
        $query = "SELECT a.*, e.nombre_completo, e.cedula, e.departamento 
                  FROM " . $this->tabla . " a 
                  INNER JOIN empleados e ON a.id_empleado = e.id_empleado 
                  WHERE a.fecha = :fecha 
                  ORDER BY a.hora_entrada DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorRangoFechas($fecha_inicio, $fecha_fin) {
        $query = "SELECT a.*, e.nombre_completo, e.cedula, e.departamento 
                  FROM " . $this->tabla . " a 
                  INNER JOIN empleados e ON a.id_empleado = e.id_empleado 
                  WHERE a.fecha BETWEEN :fecha_inicio AND :fecha_fin 
                  ORDER BY a.fecha DESC, a.hora_entrada DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":fecha_inicio", $fecha_inicio);
        $stmt->bindParam(":fecha_fin", $fecha_fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function verificarEntradaHoy($id_empleado) {
        $fecha = date('Y-m-d');
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_empleado = :id_empleado AND fecha = :fecha";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorEmpleadoYFechas($id_empleado, $fecha_inicio, $fecha_fin) {
        $query = "SELECT a.*, e.nombre_completo, e.cedula, e.departamento 
                  FROM " . $this->tabla . " a 
                  INNER JOIN empleados e ON a.id_empleado = e.id_empleado 
                  WHERE a.id_empleado = :id_empleado 
                  AND a.fecha BETWEEN :fecha_inicio AND :fecha_fin 
                  ORDER BY a.fecha DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":fecha_inicio", $fecha_inicio);
        $stmt->bindParam(":fecha_fin", $fecha_fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
