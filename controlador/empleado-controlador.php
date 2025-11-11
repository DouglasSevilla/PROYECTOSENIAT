<?php
session_start();
require_once __DIR__ . '/../modelo/empleado-modelo.php';
require_once __DIR__ . '/../modelo/historial-modelo.php';

class EmpleadoController {
    private $modeloEmpleado;
    private $modeloHistorial;
    
    public function __construct() {
        $this->modeloEmpleado = new Empleado();
        $this->modeloHistorial = new HistorialOperacion();
    }
    
    public function crear($cedula, $nombre_completo, $departamento, $fecha_nacimiento, $fecha_ingreso) {
        try {
            $resultado = $this->modeloEmpleado->crear($cedula, $nombre_completo, $departamento, $fecha_nacimiento, $fecha_ingreso);
            
            if ($resultado) {
                $this->modeloHistorial->registrar(
                    $_SESSION['id_usuario'], 
                    "Creación de empleado: " . $nombre_completo, 
                    "empleados", 
                    null
                );
                return ['success' => true, 'mensaje' => 'Empleado creado correctamente'];
            }
            
            return ['success' => false, 'mensaje' => 'Error al crear empleado'];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }
    
    public function actualizar($id, $cedula, $nombre_completo, $departamento, $fecha_nacimiento, $fecha_ingreso) {
        try {
            $resultado = $this->modeloEmpleado->actualizar($id, $cedula, $nombre_completo, $departamento, $fecha_nacimiento, $fecha_ingreso);
            
            if ($resultado) {
                $this->modeloHistorial->registrar(
                    $_SESSION['id_usuario'], 
                    "Actualización de empleado: " . $nombre_completo, 
                    "empleados", 
                    $id
                );
                return ['success' => true, 'mensaje' => 'Empleado actualizado correctamente'];
            }
            
            return ['success' => false, 'mensaje' => 'Error al actualizar empleado'];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }
    
    public function eliminar($id) {
        try {
            $empleado = $this->modeloEmpleado->obtenerPorId($id);
            $resultado = $this->modeloEmpleado->eliminar($id);
            
            if ($resultado) {
                $this->modeloHistorial->registrar(
                    $_SESSION['id_usuario'], 
                    "Eliminación de empleado: " . $empleado['nombre_completo'], 
                    "empleados", 
                    $id
                );
                return ['success' => true, 'mensaje' => 'Empleado eliminado correctamente'];
            }
            
            return ['success' => false, 'mensaje' => 'Error al eliminar empleado'];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'obtener' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    try {
        $controller = new EmpleadoController();
        $empleado = $controller->modeloEmpleado->obtenerPorId($_GET['id']);
        echo json_encode($empleado ?? []);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    header('Content-Type: application/json');
    // Logging temporal para depuración: registrar payloads POST
    try {
        $logDir = __DIR__ . '/../logs';
        if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
        $logFile = $logDir . '/empleado_debug.log';
        $entry = date('Y-m-d H:i:s') . " | POST recibida: " . PHP_EOL . print_r($_POST, true) . PHP_EOL;
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    } catch (Exception $e) {
        // no bloquear la ejecución por el log
    }
    $controller = new EmpleadoController();
    
    try {
        if ($_POST['accion'] === 'crear') {
            $resultado = $controller->crear(
                $_POST['cedula'] ?? '',
                $_POST['nombre_completo'] ?? '',
                $_POST['departamento'] ?? '',
                $_POST['fecha_nacimiento'] ?? '',
                $_POST['fecha_ingreso'] ?? ''
            );
            echo json_encode($resultado);
        } elseif ($_POST['accion'] === 'actualizar') {
            $resultado = $controller->actualizar(
                $_POST['id_empleado'] ?? '',
                $_POST['cedula'] ?? '',
                $_POST['nombre_completo'] ?? '',
                $_POST['departamento'] ?? '',
                $_POST['fecha_nacimiento'] ?? '',
                $_POST['fecha_ingreso'] ?? ''
            );
            echo json_encode($resultado);
        } elseif ($_POST['accion'] === 'eliminar') {
            $resultado = $controller->eliminar($_POST['id_empleado'] ?? '');
            echo json_encode($resultado);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
    }
    exit();
}
?>
