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
  fetch(`vista/${pagina}-vista.php`)
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
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar")
  sidebar.classList.toggle("active")
}

// Función para cerrar sesión
function cerrarSesion() {
  if (confirm("¿Está seguro que desea cerrar sesión?")) {
    const form = document.createElement("form")
    form.method = "POST"
    form.action = "controlador/login-controlador.php"

    const input = document.createElement("input")
    input.type = "hidden"
    input.name = "accion"
    input.value = "logout"

    form.appendChild(input)
    document.body.appendChild(form)
    form.submit()
  }
}

// Cerrar sidebar al hacer click fuera en móvil
document.addEventListener("click", (event) => {
  const sidebar = document.getElementById("sidebar")
  const toggle = document.getElementById("sidebarToggle")

  if (window.innerWidth <= 768) {
    if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
      sidebar.classList.remove("active")
    }
  }
})

// Cargar página de inicio al cargar el documento
document.addEventListener("DOMContentLoaded", () => {
  cargarPagina("inicio")
})
