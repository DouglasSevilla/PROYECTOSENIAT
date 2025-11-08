// Función para cargar páginas dinámicamente
function cargarPagina(pagina) {
  // Remover clase active de todos los items
  const menuItems = document.querySelectorAll(".menu-item")
  menuItems.forEach((item) => item.classList.remove("active"))

  // Agregar clase active al item clickeado
  event.currentTarget.classList.add("active")

  // Cargar el contenido de la página
  const contentDiv = document.getElementById("pageContent")

  // Mostrar loading
  contentDiv.innerHTML =
    '<div class="text-center p-5"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Cargando...</span></div></div>'

  // Hacer petición para cargar la vista
  fetch(`vistas/${pagina}-vista.php`)
    .then((response) => response.text())
    .then((html) => {
      contentDiv.innerHTML = html

      // Cerrar sidebar en móvil después de seleccionar
      if (window.innerWidth <= 768) {
        document.getElementById("sidebar").classList.remove("active")
      }
    })
    .catch((error) => {
      contentDiv.innerHTML = '<div class="alert alert-danger">Error al cargar la página</div>'
      console.error("Error:", error)
    })
}

// Función para toggle del sidebar en móvil
// Función para cargar páginas dinámicamente
function cargarPagina(pagina) {
  if (!pagina) return

  // Remover clase active de todos los items
  const menuItems = document.querySelectorAll('.menu-item')
  menuItems.forEach((item) => item.classList.remove('active'))

  // Agregar clase active al item correspondiente (si existe)
  try {
    const selector = `.menu-item[onclick*="${pagina}"]`
    const clicked = document.querySelector(selector)
    if (clicked) clicked.classList.add('active')
  } catch (e) {
    // ignore selector errors
  }

  // Cargar el contenido de la página
  const contentDiv = document.getElementById('pageContent')

  // Mostrar loading
  contentDiv.innerHTML =
    '<div class="text-center p-5"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Cargando...</span></div></div>'

  // Calcular ruta base (asegura que funcione aunque la app no esté en la raíz)
  let basePath = window.location.pathname
  if (!basePath.endsWith('/')) {
    basePath = basePath.substring(0, basePath.lastIndexOf('/') + 1)
  }
  const url = window.location.origin + basePath + 'vistas/' + pagina + '-vista.php'

  // Hacer petición para cargar la vista
  fetch(url)
    .then((response) => {
      if (!response.ok) throw new Error('HTTP ' + response.status)
      return response.text()
    })
    .then((html) => {
      contentDiv.innerHTML = html

      // Cerrar sidebar en móvil después de seleccionar
      if (window.innerWidth <= 768) {
        const sb = document.getElementById('sidebar')
        if (sb) sb.classList.remove('active')
      }
    })
    .catch((error) => {
      contentDiv.innerHTML = `<div class="alert alert-danger">Error al cargar la página: ${error.message}</div>`
      console.error('Error al cargar vista:', error)
    })
}

// Función para toggle del sidebar en móvil
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar')
  if (sidebar) sidebar.classList.toggle('active')
}

// Función para cerrar sesión
function cerrarSesion() {
  if (confirm('¿Está seguro que desea cerrar sesión?')) {
    const form = document.createElement('form')
    form.method = 'POST'
    form.action = 'controlador/login-controlador.php'

    const input = document.createElement('input')
    input.type = 'hidden'
    input.name = 'accion'
    input.value = 'logout'

    form.appendChild(input)
    document.body.appendChild(form)
    form.submit()
  }
}

// Cerrar sidebar al hacer click fuera en móvil
document.addEventListener('click', (event) => {
  const sidebar = document.getElementById('sidebar')
  const toggle = document.getElementById('sidebarToggle')

  if (window.innerWidth <= 768) {
    if (sidebar && toggle && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
      sidebar.classList.remove('active')
    }
  }
})

// Cargar página de inicio al cargar el documento
document.addEventListener('DOMContentLoaded', () => {
  cargarPagina('inicio')
})
