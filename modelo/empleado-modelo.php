<?php
require_once __DIR__ . '/../config/conexion.php';

class Empleado {
    private $conexion;
    private $tabla = "empleados";

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM {$this->tabla} WHERE activo = 1 ORDER BY nombre_completo";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        if (!$id) return null;
        $query = "SELECT * FROM {$this->tabla} WHERE id_empleado = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($cedula, $nombre_completo, $departamento, $fecha_nacimiento, $fecha_ingreso) {
        $query = "INSERT INTO {$this->tabla} (cedula, nombre_completo, departamento, fecha_nacimiento, fecha_ingreso)
                  VALUES (:cedula, :nombre, :departamento, :fecha_nac, :fecha_ing)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":cedula", $cedula);
        $stmt->bindParam(":nombre", $nombre_completo);
        $stmt->bindParam(":departamento", $departamento);
        $stmt->bindParam(":fecha_nac", $fecha_nacimiento);
        $stmt->bindParam(":fecha_ing", $fecha_ingreso);
        if (!$stmt->execute()) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al crear empleado: ' . implode(' | ', $err));
        }
        return true;
    }

    public function actualizar($id, $cedula, $nombre_completo, $departamento, $fecha_nacimiento, $fecha_ingreso) {
        $query = "UPDATE {$this->tabla}
                  SET cedula = :cedula, nombre_completo = :nombre,
                      departamento = :departamento, fecha_nacimiento = :fecha_nac, fecha_ingreso = :fecha_ing
                  WHERE id_empleado = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":cedula", $cedula);
        $stmt->bindParam(":nombre", $nombre_completo);
        $stmt->bindParam(":departamento", $departamento);
        $stmt->bindParam(":fecha_nac", $fecha_nacimiento);
        $stmt->bindParam(":fecha_ing", $fecha_ingreso);
        if (!$stmt->execute()) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al actualizar empleado: ' . implode(' | ', $err));
        }
        return true;
    }

    public function eliminar($id) {
        $query = "UPDATE {$this->tabla} SET activo = 0 WHERE id_empleado = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $err = $stmt->errorInfo();
            throw new Exception('Error al eliminar empleado: ' . implode(' | ', $err));
        }
        return true;
    }

    public function obtenerCumpleanerosMes($mes) {
        $query = "SELECT * FROM {$this->tabla}
                  WHERE MONTH(fecha_nacimiento) = :mes AND activo = 1
                  ORDER BY DAY(fecha_nacimiento)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":mes", $mes, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCumpleanosPorMes($mes) {
        $query = "SELECT *, DAY(fecha_nacimiento) AS dia_cumple
                  FROM {$this->tabla}
                  WHERE MONTH(fecha_nacimiento) = :mes AND activo = 1
                  ORDER BY DAY(fecha_nacimiento)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":mes", $mes, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProximosCumpleanos($dias = 30) {
        $query = "SELECT *,
                  DATEDIFF(
                      DATE_ADD(
                          MAKEDATE(YEAR(CURDATE()), 1),
                          INTERVAL DAYOFYEAR(fecha_nacimiento) - 1 DAY
                      ),
                      CURDATE()
                  ) AS dias_faltantes
                  FROM {$this->tabla}
                  WHERE activo = 1
                  HAVING dias_faltantes BETWEEN 0 AND :dias
                  ORDER BY dias_faltantes";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":dias", $dias, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>