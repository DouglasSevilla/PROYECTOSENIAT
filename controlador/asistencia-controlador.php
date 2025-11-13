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
        $this->modeloAsistencia = new Asistencia();
        $this->modeloEmpleado = new Empleado();
        $this->modeloHistorial = new HistorialOperacion();
    }

    public function registrarEntrada($id_empleado, $observacion = null) {
        try {
            if (!$id_empleado) {
                return ['success' => false, 'mensaje' => 'Empleado no especificado'];
            }

            $verificar = $this->modeloAsistencia->verificarEntradaHoy($id_empleado);
            if ($verificar) {
                return ['success' => false, 'mensaje' => 'El empleado ya registró entrada hoy'];
            }

            $resultado = $this->modeloAsistencia->registrarEntrada($id_empleado, $observacion);
            if ($resultado) {
                $empleado = $this->modeloEmpleado->obtenerPorId($id_empleado);
                $this->modeloHistorial->registrar(
                    $_SESSION['id_usuario'] ?? null,
                    "Registro de entrada: " . $empleado['nombre_completo'],
                    "asistencia",
                    $id_empleado
                );
                return ['success' => true, 'mensaje' => 'Entrada registrada correctamente para ' . $empleado['nombre_completo']];
            }

            return ['success' => false, 'mensaje' => 'Error al registrar entrada'];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }

    public function registrarSalida($id_empleado, $observacion = null) {
        try {
            if (!$id_empleado) {
                return ['success' => false, 'mensaje' => 'Empleado no especificado'];
            }

            $resultado = $this->modeloAsistencia->registrarSalida($id_empleado, $observacion);
            if ($resultado) {
                $empleado = $this->modeloEmpleado->obtenerPorId($id_empleado);
                $this->modeloHistorial->registrar(
                    $_SESSION['id_usuario'] ?? null,
                    "Registro de salida: " . $empleado['nombre_completo'],
                    "asistencia",
                    $id_empleado
                );
                return ['success' => true, 'mensaje' => 'Salida registrada correctamente para ' . $empleado['nombre_completo']];
            }

            return ['success' => false, 'mensaje' => 'Error al registrar salida'];
        } catch (Exception $e) {
            return ['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }

    public function obtenerAsistenciaHoy() {
        $fecha = date('Y-m-d');
        return $this->modeloAsistencia->obtenerPorFecha($fecha);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    header('Content-Type: application/json');
    $controller = new AsistenciaController();

    try {
        $id_empleado = $_POST['id_empleado'] ?? '';
        $observacion = $_POST['observacion'] ?? null;

        if ($_POST['accion'] === 'registrar_entrada') {
            echo json_encode($controller->registrarEntrada($id_empleado, $observacion));
        } elseif ($_POST['accion'] === 'registrar_salida') {
            echo json_encode($controller->registrarSalida($id_empleado, $observacion));
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Acción no reconocida']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
    }
    exit();
}
?>
