<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$rol = $_SESSION['rol'];
$nombre_usuario = $_SESSION['nombre_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema SENIAT - Nirgua</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="vistas/estilos.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>SENIAT</h4>
            <p class="mb-0">Nirgua</p>
        </div>
        
        <div class="user-info">
            <i class="fas fa-user-circle fa-2x"></i>
            <p class="mb-0 mt-2"><?php echo htmlspecialchars($nombre_usuario); ?></p>
            <small><?php echo $rol === 'Administrador' ? 'Usuario Maestro' : 'Usuario Encargado'; ?></small>
        </div>
        
        <ul class="sidebar-menu">
            <li class="menu-item active" onclick="cargarPagina('inicio', event)">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </li>
            
            <li class="menu-item" onclick="cargarPagina('asistencia', event)">
                <i class="fas fa-clock"></i>
                <span>Registro de Asistencia</span>
            </li>
            
            <?php if ($rol === 'Administrador'): ?>
            <li class="menu-item" onclick="cargarPagina('empleados', event)">
                <i class="fas fa-users"></i>
                <span>Registro de Empleados</span>
            </li>
            
            <!-- Agregar opción de gestión de usuarios en el menú -->
            <li class="menu-item" onclick="cargarPagina('usuarios', event)">
                <i class="fas fa-users-cog"></i>
                <span>Gestión de Usuarios</span>
            </li>
            
            <li class="menu-item" onclick="cargarPagina('incidencias', event)">
                <i class="fas fa-file-alt"></i>
                <span>Incidencias</span>
            </li>
            
            <li class="menu-item" onclick="cargarPagina('reportes', event)">
                <i class="fas fa-chart-bar"></i>
                <span>Reportes</span>
            </li>
            
            <li class="menu-item" onclick="cargarPagina('cumpleanos', event)">
                <i class="fas fa-birthday-cake"></i>
                <span>Cumpleaños</span>
            </li>
            
            <li class="menu-item" onclick="cargarPagina('historial', event)">
                <i class="fas fa-clipboard-list"></i>
                <span>Historial de Operaciones</span>
            </li>
            <?php endif; ?>
            
            <li class="menu-item logout-item" onclick="cerrarSesion(event)">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </li>
        </ul>
    </div>
    
    <!-- Toggle Button for Mobile -->
    <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="content-wrapper">
            <div id="pageContent">
                <!-- El contenido se cargará aquí dinámicamente -->
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="vistas/menu.js"></script>
</body>
</html>

