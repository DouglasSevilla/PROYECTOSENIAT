<?php
require_once __DIR__ . '/../modelo/usuario-modelo.php';
$usuarioModelo = new Usuario();
$usuarios = $usuarioModelo->obtenerTodos();
$rolActual = $_SESSION['rol'] ?? 'Encargado';
?>

<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-12">
      <h2 class="text-seniat-rojo"><i class="fas fa-users-cog me-2"></i>Gestión de Usuarios</h2>
      <p class="text-muted">Administra los usuarios del sistema SENIAT</p>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-12">
      <button class="btn btn-seniat-rojo" data-bs-toggle="modal" data-bs-target="#modalUsuario">
        <i class="fas fa-plus-circle me-2"></i>Nuevo Usuario
      </button>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="tablaUsuarios">
              <thead class="table-seniat">
                <tr>
                  <th>Usuario</th>
                  <th>Rol</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($usuarios as $usr): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($usr['nombre_usuario']) ?></strong></td>
                  <td><?= $usr['rol'] === 'Administrador' ? 'Usuario Maestro' : 'Usuario Encargado' ?></td>
                  <td><span class="badge bg-<?= $usr['activo'] ? 'success' : 'danger' ?>">
                    <?= $usr['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
                  <td>
                    <button class="btn btn-sm btn-warning" onclick="editarUsuario(<?= $usr['id_usuario'] ?>)">
                      <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(<?= $usr['id_usuario'] ?>)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
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

<!-- Modal para agregar/editar usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-seniat-rojo text-white">
        <h5 class="modal-title">Nuevo Usuario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formUsuario">
        <div class="modal-body">
          <input type="hidden" name="id_usuario" id="id_usuario">

          <div class="mb-3">
            <label class="form-label"><i class="fas fa-user me-2"></i>Nombre de Usuario</label>
            <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" required>
          </div>

          <div class="mb-3" id="divClave">
            <label class="form-label"><i class="fas fa-lock me-2"></i>Contraseña</label>
            <input type="password" class="form-control" name="clave" id="clave" required>
            <small class="text-muted">Mínimo 6 caracteres</small>
          </div>

          <div class="mb-3">
            <label class="form-label"><i class="fas fa-shield-alt me-2"></i>Rol</label>
            <select class="form-select" name="rol" id="rol" required>
              <option value="">-- Seleccione un rol --</option>
              <option value="Administrador">Usuario Maestro</option>
              <option value="Encargado">Usuario Encargado</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" id="btnGuardarUsuario" class="btn btn-seniat-rojo">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('btnGuardarUsuario').addEventListener('click', function(){
  const form = document.getElementById('formUsuario');
  const formData = new FormData(form);
  const id = formData.get('id_usuario');

  if (id) {
    formData.append('accion', 'actualizar');
    if (!formData.get('clave')) formData.delete('clave');
  } else {
    formData.append('accion', 'crear');
  }

  fetch('controlador/usuario-controlador.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(text => {
    const [status, mensaje] = text.split('|');
    if (status === 'OK') {
      const bsModal = bootstrap.Modal.getInstance(document.getElementById('modalUsuario'));
      if (bsModal) bsModal.hide();
      cargarPagina('usuarios');
    } else {
      alert(mensaje || 'Error al guardar');
    }
  })
  .catch(err => {
    console.error(err);
    alert('Error de red');
  });
});

function editarUsuario(id) {
  fetch('controlador/usuario-controlador.php?action=obtener&id=' + encodeURIComponent(id))
    .then(res => res.text())
    .then(html => {
      const temp = document.createElement('div');
      temp.innerHTML = html;
      const modal = document.getElementById('modalUsuario');
      modal.querySelector('.modal-title').textContent = 'Editar Usuario';
      modal.querySelector('#id_usuario').value = temp.querySelector('#usr_id_usuario')?.value || '';
      modal.querySelector('#nombre_usuario').value = temp.querySelector('#usr_nombre_usuario')?.value || '';
      modal.querySelector('#rol').value = temp.querySelector('#usr_rol')?.value || '';
      document.getElementById('divClave').style.display = 'none';
      modal.querySelector('#clave').removeAttribute('required');
      const bsModal = new bootstrap.Modal(modal);
      bsModal.show();
    })
    .catch(err => {
      console.error(err);
      alert('No se pudo cargar datos del usuario');
    });
}

function eliminarUsuario(id) {
  if (!confirm('¿Eliminar este usuario?')) return;
  const fd = new FormData();
  fd.append('accion', 'eliminar');
  fd.append('id_usuario', id);
  fetch('controlador/usuario-controlador.php', { method: 'POST', body: fd })
    .then(res => res.text())
    .then(text => {
      const [status, mensaje] = text.split('|');
      if (status === 'OK') cargarPagina('usuarios');
      else alert(mensaje || 'Error al eliminar');
    })
    .catch(err => {
      console.error(err);
      alert('Error de red');
    });
}

const modalUsuario = document.getElementById('modalUsuario');
if (modalUsuario) {
  modalUsuario.addEventListener('show.bs.modal', function(){
    const form = document.getElementById('formUsuario');
    form.reset();
    document.getElementById('divClave').style.display = 'block';
    modalUsuario.querySelector('.modal-title').textContent = 'Nuevo Usuario';
    document.getElementById('nombre_usuario').focus();
  });
}
</script>