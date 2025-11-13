<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelo/asistencia-modelo.php';
require_once __DIR__ . '/../modelo/empleado-modelo.php';
require_once __DIR__ . '/../modelo/incidencia-modelo.php';
require_once __DIR__ . '/../modelo/historial-modelo.php';

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit();
}

$asistenciaModelo = new Asistencia();
$empleadoModelo = new Empleado();
$incidenciaModelo = new Incidencia();
$historialModelo = new HistorialOperacion();

// Procesar solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $accion = $_POST['accion'] ?? '';
    try {
        if ($accion === 'generar_reporte_asistencia') {
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $fecha_fin = $_POST['fecha_fin'] ?? '';
            $id_empleado = $_POST['id_empleado'] ?? null;
            if ($id_empleado) {
                $registros = $asistenciaModelo->obtenerPorEmpleadoYFechas($id_empleado, $fecha_inicio, $fecha_fin);
            } else {
                $registros = $asistenciaModelo->obtenerPorRangoFechas($fecha_inicio, $fecha_fin);
            }
            $historialModelo->registrar(
                $_SESSION['id_usuario'],
                'Generó reporte de asistencia',
                'asistencia',
                null
            );
            echo json_encode(['success' => true, 'data' => $registros]);
            exit();
        }
        if ($accion === 'generar_reporte_incidencias') {
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $fecha_fin = $_POST['fecha_fin'] ?? '';
            $tipo = $_POST['tipo_incidencia'] ?? null;
            $registros = $incidenciaModelo->obtenerPorRangoFechas($fecha_inicio, $fecha_fin, $tipo);
            $historialModelo->registrar(
                $_SESSION['id_usuario'],
                'Generó reporte de incidencias',
                'incidencias',
                null
            );
            echo json_encode(['success' => true, 'data' => $registros]);
            exit();
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        exit();
    }
}

// Obtener datos para los filtros
$empleados = $empleadoModelo->obtenerTodos();
?>
