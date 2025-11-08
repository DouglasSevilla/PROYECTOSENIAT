<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelo/empleado-modelo.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

$empleadoModelo = new Empleado();

// Obtener cumpleaños del mes actual
$mes_actual = date('m');
$cumpleanos_mes = $empleadoModelo->obtenerCumpleanosPorMes($mes_actual);

// Obtener próximos cumpleaños (próximos 30 días)
$proximos_cumpleanos = $empleadoModelo->obtenerProximosCumpleanos(30);
?>
