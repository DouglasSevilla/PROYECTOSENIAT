<?php
session_start();
require_once __DIR__ . '/../modelo/asistencia-modelo.php';
require_once __DIR__ . '/../modelo/empleado-modelo.php';
require_once __DIR__ . '/../modelo/historial-modelo.php';

class AsistenciaController {
    private $modeloAsistencia;
    private $modeloEmpleado;
    private $modeloHistorial;
    
    public function __construct() {
        $this->modeloAsistencia = new AsistenciaModelo();
        $this->modeloEmpleado = new EmpleadoModelo();
        $this->modeloHistorial = new HistorialModelo();
    }
    
    public function registrarEntrada($id_empleado, $observacion = null) {
        $verificar = $this->modeloAsistencia->verificarEntradaHoy($id_empleado);
        
        if ($verificar) {
            return ['success' => false, 'mensaje' => 'El empleado ya registró entrada hoy'];
        }
        
        $resultado = $this->modeloAsistencia->registrarEntrada($id_empleado, $observacion);
        
        if ($resultado) {
            $empleado = $this->modeloEmpleado->obtenerPorId($id_empleado);
            $this->modeloHistorial->registrar(
                $_SESSION['id_usuario'], 
                "Registro de entrada: " . $empleado['nombre_completo'], 
                "asistencia", 
                $id_empleado
            );
            return ['success' => true, 'mensaje' => 'Entrada registrada correctamente'];
        }
        
        return ['success' => false, 'mensaje' => 'Error al registrar entrada'];
    }
    
    public function registrarSalida($id_empleado, $observacion = null) {
        $resultado = $this->modeloAsistencia->registrarSalida($id_empleado, $observacion);
        
        if ($resultado) {
            $empleado = $this->modeloEmpleado->obtenerPorId($id_empleado);
            $this->modeloHistorial->registrar(
                $_SESSION['id_usuario'], 
                "Registro de salida: " . $empleado['nombre_completo'], 
                "asistencia", 
                $id_empleado
            );
            return ['success' => true, 'mensaje' => 'Salida registrada correctamente'];
        }
        
        return ['success' => false, 'mensaje' => 'Error al registrar salida'];
    }
    
    public function obtenerAsistenciaHoy() {
        $fecha = date('Y-m-d');
        return $this->modeloAsistencia->obtenerPorFecha($fecha);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $controller = new AsistenciaController();
    
    if ($_POST['accion'] === 'registrar_entrada') {
        $resultado = $controller->registrarEntrada($_POST['id_empleado'], $_POST['observacion'] ?? null);
        echo json_encode($resultado);
    } elseif ($_POST['accion'] === 'registrar_salida') {
        $resultado = $controller->registrarSalida($_POST['id_empleado'], $_POST['observacion'] ?? null);
        echo json_encode($resultado);
    }
}
?>
