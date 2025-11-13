<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelo/historial-modelo.php';

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit();
}

// Solo usuarios Administrador pueden ver el historial completo
if ($_SESSION['rol'] !== 'Administrador') {
    header('Location: ../index.php?page=inicio');
    exit();
}

$historialModelo = new HistorialOperacion();

// Obtener parámetros de filtro
$fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
$usuario = $_GET['usuario'] ?? null;
$tabla = $_GET['tabla'] ?? null;

// Obtener historial
if ($usuario || $tabla) {
    $operaciones = $historialModelo->obtenerPorFiltros($fecha_inicio, $fecha_fin, $usuario, $tabla);
} else {
    $operaciones = $historialModelo->obtenerPorRangoFechas($fecha_inicio, $fecha_fin);
}
?>
