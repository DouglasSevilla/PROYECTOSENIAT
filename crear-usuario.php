<?php
require_once __DIR__ . '/modelo/usuario-modelo.php';

$usuario = new Usuario();
$usuario->crear("admin", "admin123", "Administrador");

echo "Usuario creado correctamente.";
?>