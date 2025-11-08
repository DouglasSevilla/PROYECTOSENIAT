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
    }
    
    public function actualizar($id, $cedula, $nombre_completo, $departamento, $fecha_nacimiento, $fecha_ingreso) {
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
    }
    
    public function eliminar($id) {
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
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $controller = new EmpleadoController();
    
    if ($_POST['accion'] === 'crear') {
        $resultado = $controller->crear(
            $_POST['cedula'],
            $_POST['nombre_completo'],
            $_POST['departamento'],
            $_POST['fecha_nacimiento'],
            $_POST['fecha_ingreso']
        );
        echo json_encode($resultado);
    } elseif ($_POST['accion'] === 'actualizar') {
        $resultado = $controller->actualizar(
            $_POST['id_empleado'],
            $_POST['cedula'],
            $_POST['nombre_completo'],
            $_POST['departamento'],
            $_POST['fecha_nacimiento'],
            $_POST['fecha_ingreso']
        );
        echo json_encode($resultado);
    } elseif ($_POST['accion'] === 'eliminar') {
        $resultado = $controller->eliminar($_POST['id_empleado']);
        echo json_encode($resultado);
    }
}
?>
