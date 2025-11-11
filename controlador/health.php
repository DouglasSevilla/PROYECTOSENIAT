<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/conexion.php';
try {
    $db = new Conexion();
    $pdo = $db->conectar();
    if (!$pdo) throw new Exception('No se pudo conectar a la BD');

    $res = [];
    // Test empleados count
    $stmt = $pdo->prepare('SELECT COUNT(*) as c FROM empleados');
    $stmt->execute();
    $res['empleados'] = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;

    // Test asistencia count
    $stmt = $pdo->prepare('SELECT COUNT(*) as c FROM asistencia');
    $stmt->execute();
    $res['asistencia'] = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;

    echo json_encode(['success' => true, 'data' => $res]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'mensaje' => $e->getMessage()]);
}
