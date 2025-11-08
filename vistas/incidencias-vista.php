<?php
// Vista para gestión de incidencias
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-seniat-rojo"><i class="bi bi-file-text me-2"></i>Gestión de Incidencias</h2>
            <p class="text-muted">Registra permisos, ausencias y otras incidencias del personal</p>
        </div>
    </div>

    <!-- Botón para nueva incidencia -->
    <div class="row mb-3">
        <div class="col-12">
            <button class="btn btn-seniat-rojo" data-bs-toggle="modal" data-bs-target="#modalIncidencia">
                <i class="bi bi-plus-circle me-2"></i>Nueva Incidencia
            </button>
        </div>
    </div>

    <!-- Tabla de incidencias -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-seniat">
                                <tr>
                                    <th>Empleado</th>
                                    <th>Tipo</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once '../modelo/incidencia-modelo.php';
                                $incidenciaModelo = new Incidencia();
                                $incidencias = $incidenciaModelo->obtenerTodas();
                                
                                foreach ($incidencias as $inc) {
                                    echo "<tr>
                                        <td>{$inc['nombre_empleado']}</td>
                                        <td><span class='badge bg-info'>{$inc['tipo_incidencia']}</span></td>
                                        <td>" . date('d/m/Y', strtotime($inc['fecha_inicio'])) . "</td>
                                        <td>" . date('d/m/Y', strtotime($inc['fecha_fin'])) . "</td>
                                        <td>{$inc['descripcion']}</td>
                                        <td>
                                            <button class='btn btn-sm btn-danger' onclick='eliminarIncidencia({$inc['id_incidencia']})'>
                                                <i class='bi bi-trash'></i>
                                            </button>
                                        </td>
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

<!-- Modal para nueva incidencia -->
<div class="modal fade" id="modalIncidencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-seniat-rojo text-white">
                <h5 class="modal-title">Registrar Incidencia</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?page=incidencias&action=guardar">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Empleado</label>
                        <select class="form-select" name="id_empleado" required>
                            <option value="">Seleccione...</option>
                            <?php
                            require_once '../modelo/empleado-modelo.php';
                            $empleadoModelo = new Empleado();
                            $empleados = $empleadoModelo->obtenerActivos();
                            foreach ($empleados as $emp) {
                                echo "<option value='{$emp['id_empleado']}'>{$emp['nombre_completo']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipo de Incidencia</label>
                        <select class="form-select" name="tipo_incidencia" required>
                            <option value="">Seleccione...</option>
                            <option value="Permiso">Permiso</option>
                            <option value="Ausencia">Ausencia</option>
                            <option value="Tardanza">Tardanza</option>
                            <option value="Vacaciones">Vacaciones</option>
                            <option value="Reposo Médico">Reposo Médico</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" name="fecha_fin" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-seniat-rojo">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
