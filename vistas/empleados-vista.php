<?php
// Vista para gestión de empleados
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-seniat-rojo"><i class="bi bi-people-fill me-2"></i>Gestión de Empleados</h2>
            <p class="text-muted">Administra el registro de empleados del SENIAT Nirgua</p>
        </div>
    </div>

    <!-- Botón para agregar nuevo empleado -->
    <div class="row mb-3">
        <div class="col-12">
            <button class="btn btn-seniat-rojo" data-bs-toggle="modal" data-bs-target="#modalEmpleado">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Empleado
            </button>
        </div>
    </div>

    <!-- Tabla de empleados -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaEmpleados">
                            <thead class="table-seniat">
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre Completo</th>
                                    <th>Departamento</th>
                                    <th>Fecha Nacimiento</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once __DIR__ . '/../modelo/empleado-modelo.php';
                                $empleadoModelo = new Empleado();
                                $empleados = $empleadoModelo->obtenerTodos();
                                
                                foreach ($empleados as $emp) {
                                    $estadoClass = $emp['activo'] ? 'success' : 'danger';
                                    $estadoTexto = $emp['activo'] ? 'Activo' : 'Inactivo';
                                    echo "<tr>
                                        <td>{$emp['cedula']}</td>
                                        <td>{$emp['nombre_completo']}</td>
                                        <td>{$emp['departamento']}</td>
                                        <td>" . date('d/m/Y', strtotime($emp['fecha_nacimiento'])) . "</td>
                                        <td>" . date('d/m/Y', strtotime($emp['fecha_ingreso'])) . "</td>
                                        <td><span class='badge bg-{$estadoClass}'>{$estadoTexto}</span></td>
                                        <td>
                                            <button class='btn btn-sm btn-warning' onclick='editarEmpleado({$emp['id_empleado']})'>
                                                <i class='bi bi-pencil'></i>
                                            </button>
                                            <button class='btn btn-sm btn-danger' onclick='eliminarEmpleado({$emp['id_empleado']})'>
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

<!-- Modal para agregar/editar empleado -->
<div class="modal fade" id="modalEmpleado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-seniat-rojo text-white">
                <h5 class="modal-title">Registrar Empleado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?page=empleados&action=guardar">
                <div class="modal-body">
                    <input type="hidden" name="id_empleado" id="id_empleado">
                    
                    <div class="mb-3">
                        <label class="form-label">Cédula</label>
                        <input type="text" class="form-control" name="cedula" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" name="nombre_completo" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Departamento</label>
                        <input type="text" class="form-control" name="departamento" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" name="fecha_nacimiento" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" name="fecha_ingreso" required>
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
