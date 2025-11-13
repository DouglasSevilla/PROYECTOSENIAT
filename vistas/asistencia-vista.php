<?php
require_once __DIR__ . '/../modelo/empleado-modelo.php';
require_once __DIR__ . '/../modelo/asistencia-modelo.php';

$modeloEmpleado = new Empleado();
$modeloAsistencia = new Asistencia();

$empleados = $modeloEmpleado->obtenerTodos();
$asistenciaHoy = $modeloAsistencia->obtenerPorFecha(date('Y-m-d'));
?>

<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-12">
      <h2 class="mb-3"><i class="fas fa-clock"></i> Registro de Asistencia</h2>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-sign-in-alt"></i> Registrar Entrada
        </div>
        <div class="card-body">
          <form id="formEntrada">
            <div class="mb-3">
              <label for="empleadoEntrada" class="form-label">Seleccionar Empleado</label>
              <select class="form-select" id="empleadoEntrada" name="id_empleado" required>
                <option value="">-- Seleccione un empleado --</option>
                <?php foreach ($empleados as $emp): ?>
                <option value="<?php echo $emp['id_empleado']; ?>">
                  <?php echo htmlspecialchars($emp['cedula'] . ' - ' . $emp['nombre_completo']); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="observacionEntrada" class="form-label">Observación (Opcional)</label>
              <textarea class="form-control" id="observacionEntrada" name="observacion" rows="2"></textarea>
            </div>

            <button type="submit" class="btn btn-success w-100">
              <i class="fas fa-check"></i> Registrar Entrada
            </button>
          </form>
          <div id="mensajeEntrada" class="mt-3"></div>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-sign-out-alt"></i> Registrar Salida
        </div>
        <div class="card-body">
          <form id="formSalida">
            <div class="mb-3">
              <label for="empleadoSalida" class="form-label">Seleccionar Empleado</label>
              <select class="form-select" id="empleadoSalida" name="id_empleado" required>
                <option value="">-- Seleccione un empleado --</option>
                <?php foreach ($empleados as $emp): ?>
                <option value="<?php echo $emp['id_empleado']; ?>">
                  <?php echo htmlspecialchars($emp['cedula'] . ' - ' . $emp['nombre_completo']); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="observacionSalida" class="form-label">Observación (Opcional)</label>
              <textarea class="form-control" id="observacionSalida" name="observacion" rows="2"></textarea>
            </div>

            <button type="submit" class="btn btn-danger w-100">
              <i class="fas fa-times"></i> Registrar Salida
            </button>
          </form>
          <div id="mensajeSalida" class="mt-3"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-list"></i> Asistencia del Día
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Cédula</th>
                  <th>Nombre</th>
                  <th>Departamento</th>
                  <th>Hora Entrada</th>
                  <th>Hora Salida</th>
                  <th>Observación</th>
                </tr>
              </thead>
              <tbody id="tablaAsistencia">
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
                  <td><?php echo htmlspecialchars($registro['observacion'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('formEntrada').addEventListener('submit', function(event) {
  event.preventDefault();
  const formData = new FormData(this);
  formData.append('accion', 'registrar_entrada');

  fetch('controlador/asistencia-controlador.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    const mensaje = document.getElementById('mensajeEntrada');
    mensaje.innerHTML = `<div class="alert alert-${data.success ? 'success' : 'danger'}">${data.mensaje}</div>`;
    if (data.success) {
      this.reset();
      setTimeout(() => location.reload(), 1500);
    }
  });
});

document.getElementById('formSalida').addEventListener('submit', function(event) {
  event.preventDefault();
  const formData = new FormData(this);
  formData.append('accion', 'registrar_salida');

  fetch('controlador/asistencia-controlador.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    const mensaje = document.getElementById('mensajeSalida');
    mensaje.innerHTML = `<div class="alert alert-${data.success ? 'success' : 'danger'}">${data.mensaje}</div>`;
    if (data.success) {
      this.reset();
      setTimeout(() => location.reload(), 1500);
    }
  });
});
</script>