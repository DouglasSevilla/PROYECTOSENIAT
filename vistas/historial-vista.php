<?php
// Vista para historial de operaciones
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-seniat-rojo"><i class="bi bi-clock-history me-2"></i>Historial de Operaciones</h2>
            <p class="text-muted">Registro de todas las operaciones realizadas en el sistema</p>
        </div>
    </div>

    <!-- Tabla de historial -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-seniat">
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Tabla Afectada</th>
                                    <th>ID Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once '../modelo/historial-modelo.php';
                                $historialModelo = new HistorialOperacion();
                                $operaciones = $historialModelo->obtenerRecientes(50);
                                
                                foreach ($operaciones as $op) {
                                    $badgeClass = '';
                                    switch($op['accion']) {
                                        case 'INSERT': $badgeClass = 'success'; break;
                                        case 'UPDATE': $badgeClass = 'warning'; break;
                                        case 'DELETE': $badgeClass = 'danger'; break;
                                        default: $badgeClass = 'secondary';
                                    }
                                    
                                    echo "<tr>
                                        <td>" . date('d/m/Y H:i:s', strtotime($op['fecha_hora'])) . "</td>
                                        <td>{$op['nombre_usuario']}</td>
                                        <td><span class='badge bg-{$badgeClass}'>{$op['accion']}</span></td>
                                        <td>{$op['tabla_afectada']}</td>
                                        <td>{$op['id_registro_afectado']}</td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
