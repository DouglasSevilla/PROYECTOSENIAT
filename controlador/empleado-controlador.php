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
                    $_SESSION['id_usuario'] ?? null,
                    "Creación de empleado: $nombre_completo",
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
                    $_SESSION['id_usuario'] ?? null,
                    "Actualización de empleado: $nombre_completo",
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
                    $_SESSION['id_usuario'] ?? null,
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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtener' && isset($_GET['id'])) {
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
    $controller = new EmpleadoController();

    try {
        switch ($_POST['accion']) {
            case 'crear':
                echo json_encode($controller->crear(
                    $_POST['cedula'] ?? '',
                    $_POST['nombre_completo'] ?? '',
                    $_POST['departamento'] ?? '',
                    $_POST['fecha_nacimiento'] ?? '',
                    $_POST['fecha_ingreso'] ?? ''
                ));
                break;
            case 'actualizar':
                echo json_encode($controller->actualizar(
                    $_POST['id_empleado'] ?? '',
                    $_POST['cedula'] ?? '',
                    $_POST['nombre_completo'] ?? '',
                    $_POST['departamento'] ?? '',
                    $_POST['fecha_nacimiento'] ?? '',
                    $_POST['fecha_ingreso'] ?? ''
                ));
                break;
            case 'eliminar':
                echo json_encode($controller->eliminar($_POST['id_empleado'] ?? ''));
                break;
            default:
                echo json_encode(['success' => false, 'mensaje' => 'Acción no reconocida']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
    }
    exit();
}
?>