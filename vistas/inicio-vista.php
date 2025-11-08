<?php
require_once __DIR__ . '/../modelo/empleado-modelo.php';
require_once __DIR__ . '/../modelo/asistencia-modelo.php';

$modeloEmpleado = new Empleado();
$modeloAsistencia = new Asistencia();

$totalEmpleados = count($modeloEmpleado->obtenerTodos());
$asistenciaHoy = $modeloAsistencia->obtenerPorFecha(date('Y-m-d'));
$presentesHoy = count($asistenciaHoy);
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3"><i class="fas fa-home"></i> Panel de Inicio</h2>
            <p class="text-muted">Bienvenido al Sistema de Control de Asistencia del SENIAT - Sede Nirgua</p>
        </div>
    </div>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card" style="border-left: 4px solid #DC143C;">
                <div class="icon" style="color: #DC143C;">
                    <i class="fas fa-users"></i>
                </div>
                <h3><?php echo $totalEmpleados; ?></h3>
                <p>Total de Empleados</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card" style="border-left: 4px solid #28a745;">
                <div class="icon" style="color: #28a745;">
                    <i class="fas fa-user-check"></i>
                </div>
                <h3><?php echo $presentesHoy; ?></h3>
                <p>Presentes Hoy</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card" style="border-left: 4px solid #ffc107;">
                <div class="icon" style="color: #ffc107;">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <h3><?php echo date('d/m/Y'); ?></h3>
                <p>Fecha Actual</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clock"></i> Asistencia de Hoy
                </div>
                <div class="card-body">
                    <?php if (count($asistenciaHoy) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Departamento</th>
                                    <th>Hora Entrada</th>
                                    <th>Hora Salida</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($asistenciaHoy as $registro): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($registro['cedula']); ?></td>
                                    <td><?php echo htmlspecialchars($registro['nombre_completo']); ?></td>
                                    <td><?php echo htmlspecialchars($registro['departamento']); ?></td>
                                    <td><span class="badge bg-success"><?php echo date('h:i A', strtotime($registro['hora_entrada'])); ?></span></td>
                                    <td>
                                        <?php if ($registro['hora_salida']): ?>
                                            <span class="badge bg-danger"><?php echo date('h:i A', strtotime($registro['hora_salida'])); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($registro['hora_salida']): ?>
                                            <span class="badge bg-secondary">Completado</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">En Oficina</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay registros de asistencia para el día de hoy.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
