// menu.js - controlador del menú lateral y carga dinámica de vistas

function cargarPagina(pagina, ev) {
  if (!pagina) return;

  // Limpiar activo
  const menuItems = document.querySelectorAll('.menu-item');
  menuItems.forEach((item) => item.classList.remove('active'));

  // Marcar el item clickeado si se pasó el evento
  if (ev && ev.currentTarget) {
    ev.currentTarget.classList.add('active');
  } else {
    try {
      const selector = `.menu-item[onclick*=\"${pagina}\"]`;
      const clicked = document.querySelector(selector);
      if (clicked) clicked.classList.add('active');
    } catch (e) {}
  }

  const contentDiv = document.getElementById('pageContent');
  if (!contentDiv) return;

  contentDiv.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Cargando...</span></div></div>';

  const url = 'vistas/' + pagina + '-vista.php';
  fetch(url)
    .then((response) => {
      if (!response.ok) throw new Error('HTTP ' + response.status);
      return response.text();
    })
    .then((html) => {
      contentDiv.innerHTML = html;
      if (window.innerWidth <= 768) {
        const sb = document.getElementById('sidebar');
        if (sb) sb.classList.remove('active');
      }
    })
    .catch((error) => {
      contentDiv.innerHTML = `<div class="alert alert-danger">Error al cargar la página: ${error.message}</div>`;
      console.error('Error al cargar vista:', error);
    });
}

function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar) sidebar.classList.toggle('active');
}

function cerrarSesion() {
  if (confirm('¿Está seguro que desea cerrar sesión?')) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'controlador/login-controlador.php';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'accion';
    input.value = 'logout';

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }
}

document.addEventListener('click', (event) => {
  const sidebar = document.getElementById('sidebar');
  const toggle = document.getElementById('sidebarToggle');
  if (window.innerWidth <= 768) {
    if (sidebar && toggle && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
      sidebar.classList.remove('active');
    }
  }
});

document.addEventListener('DOMContentLoaded', () => {
  cargarPagina('inicio');
});
