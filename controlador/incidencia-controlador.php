<?php
session_start();
require_once __DIR__ . '/../modelo/incidencia-modelo.php';
require_once __DIR__ . '/../modelo/historial-modelo.php';

class IncidenciaController {
    private $modeloIncidencia;
    private $modeloHistorial;
    
    public function __construct() {
        $this->modeloIncidencia = new Incidencia();
        $this->modeloHistorial = new HistorialOperacion();
    }
    
    public function crear($id_empleado, $tipo_incidencia, $fecha_inicio, $fecha_fin, $descripcion) {
        $resultado = $this->modeloIncidencia->crear($id_empleado, $tipo_incidencia, $fecha_inicio, $fecha_fin, $descripcion);
        
        if ($resultado) {
            $this->modeloHistorial->registrar(
                $_SESSION['id_usuario'], 
                "Creación de incidencia: " . $tipo_incidencia, 
                "incidencias", 
                null
            );
            return ['success' => true, 'mensaje' => 'Incidencia creada correctamente'];
        }
        
        return ['success' => false, 'mensaje' => 'Error al crear incidencia'];
    }
    
    public function actualizar($id, $tipo_incidencia, $fecha_inicio, $fecha_fin, $descripcion) {
        $resultado = $this->modeloIncidencia->actualizar($id, $tipo_incidencia, $fecha_inicio, $fecha_fin, $descripcion);
        
        if ($resultado) {
            $this->modeloHistorial->registrar(
                $_SESSION['id_usuario'], 
                "Actualización de incidencia ID: " . $id, 
                "incidencias", 
                $id
            );
            return ['success' => true, 'mensaje' => 'Incidencia actualizada correctamente'];
        }
        
        return ['success' => false, 'mensaje' => 'Error al actualizar incidencia'];
    }
    
    public function eliminar($id) {
        $resultado = $this->modeloIncidencia->eliminar($id);
        
        if ($resultado) {
            $this->modeloHistorial->registrar(
                $_SESSION['id_usuario'], 
                "Eliminación de incidencia ID: " . $id, 
                "incidencias", 
                $id
            );
            return ['success' => true, 'mensaje' => 'Incidencia eliminada correctamente'];
        }
        
        return ['success' => false, 'mensaje' => 'Error al eliminar incidencia'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $controller = new IncidenciaController();
    
    if ($_POST['accion'] === 'crear') {
        $resultado = $controller->crear(
            $_POST['id_empleado'],
            $_POST['tipo_incidencia'],
            $_POST['fecha_inicio'],
            $_POST['fecha_fin'],
            $_POST['descripcion']
        );
        echo json_encode($resultado);
    } elseif ($_POST['accion'] === 'actualizar') {
        $resultado = $controller->actualizar(
            $_POST['id_incidencia'],
            $_POST['tipo_incidencia'],
            $_POST['fecha_inicio'],
            $_POST['fecha_fin'],
            $_POST['descripcion']
        );
        echo json_encode($resultado);
    } elseif ($_POST['accion'] === 'eliminar') {
        $resultado = $controller->eliminar($_POST['id_incidencia']);
        echo json_encode($resultado);
    }
}
?>
