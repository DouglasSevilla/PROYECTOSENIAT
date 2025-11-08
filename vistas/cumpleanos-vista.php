<?php
// Vista para cumpleaños
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-seniat-rojo"><i class="bi bi-cake2 me-2"></i>Cumpleaños</h2>
            <p class="text-muted">Cumpleaños del personal del SENIAT Nirgua</p>
        </div>
    </div>

    <!-- Cumpleaños del mes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-seniat-rojo text-white">
                    <h5 class="mb-0">Cumpleaños del Mes</h5>
                </div>
                <div class="card-body">
                    <?php
                    require_once '../modelo/empleado-modelo.php';
                    $empleadoModelo = new Empleado();
                    $cumpleanos = $empleadoModelo->obtenerCumpleanosMes();
                    
                    if (count($cumpleanos) > 0) {
                        echo '<div class="row">';
                        foreach ($cumpleanos as $emp) {
                            $fecha = date('d/m', strtotime($emp['fecha_nacimiento']));
                            echo "
                            <div class='col-md-4 mb-3'>
                                <div class='card border-seniat-rojo'>
                                    <div class='card-body text-center'>
                                        <i class='bi bi-person-circle display-4 text-seniat-rojo'></i>
                                        <h5 class='mt-2'>{$emp['nombre_completo']}</h5>
                                        <p class='text-muted mb-0'>{$emp['departamento']}</p>
                                        <p class='text-seniat-vinotinto fw-bold'><i class='bi bi-calendar-event me-1'></i>{$fecha}</p>
                                    </div>
                                </div>
                            </div>";
                        }
                        echo '</div>';
                    } else {
                        echo '<p class="text-center text-muted py-4">No hay cumpleaños este mes</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
