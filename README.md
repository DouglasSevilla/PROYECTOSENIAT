# Sistema de Control SENIAT - Nirgua

Sistema web institucional para el control de asistencia y gestión de empleados del SENIAT, sede Nirgua.

## Características

- **Control de Asistencia**: Registro de entrada y salida de empleados
- **Gestión de Empleados**: CRUD completo de información de empleados
- **Sistema de Incidencias**: Registro y seguimiento de incidencias
- **Reportes**: Generación de reportes de asistencia
- **Cumpleaños**: Visualización de cumpleaños de empleados
- **Historial**: Registro de todas las operaciones del sistema
- **Roles de Usuario**: Usuario Maestro (acceso completo) y Usuario Encargado (solo asistencia)

## Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5
- **Base de Datos**: MySQL 5.7+
- **Iconos**: Font Awesome 6
- **Arquitectura**: MVC (Modelo-Vista-Controlador)

## Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensiones PHP: mysqli, session

## Instalación

1. **Clonar o descargar el proyecto** en la carpeta de tu servidor web (htdocs, www, etc.)

2. **Crear la base de datos**:
   - Importar el archivo `database/schema.sql` en MySQL
   - Esto creará la base de datos `seniat_sistema` con todas las tablas necesarias

3. **Configurar la conexión a la base de datos**:
   - Editar el archivo `config/database.php`
   - Ajustar las credenciales según tu configuración:
     \`\`\`php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'tu_usuario');
     define('DB_PASS', 'tu_contraseña');
     define('DB_NAME', 'seniat_sistema');
     \`\`\`

4. **Acceder al sistema**:
   - Abrir en el navegador: `http://localhost/nombre-carpeta/login.php`

## Usuarios por Defecto

El sistema incluye dos usuarios de prueba:

**Usuario Maestro (acceso completo)**
- Usuario: `admin`
- Contraseña: `seniat2025`

**Usuario Encargado (solo asistencia)**
- Usuario: `encargado`
- Contraseña: `seniat2025`

## Estructura del Proyecto

\`\`\`
seniat-sistema/
├── api/                    # Endpoints API
│   ├── asistencia.php
│   └── empleados.php
├── assets/                 # Recursos estáticos
│   ├── css/
│   │   ├── styles.css
│   │   └── login.css
│   └── js/
│       └── main.js
├── config/                 # Configuración
│   └── database.php
├── controllers/            # Controladores MVC
│   ├── AuthController.php
│   ├── AsistenciaController.php
│   ├── EmpleadoController.php
│   └── PageController.php
├── database/              # Scripts SQL
│   └── schema.sql
├── views/                 # Vistas
│   ├── inicio.php
│   ├── asistencia.php
│   └── empleados.php
├── index.php              # Página principal
├── login.php              # Página de login
└── README.md
\`\`\`

## Funcionalidades por Módulo

### Inicio
- Dashboard con estadísticas generales
- Resumen de asistencias del día
- Información del usuario actual

### Registro de Asistencia
- Registro de entrada de empleados
- Registro de salida de empleados
- Visualización de asistencias del día en tiempo real

### Registro de Empleados (Solo Usuario Maestro)
- Crear nuevos empleados
- Editar información de empleados
- Eliminar empleados (eliminación lógica)
- Listado completo de empleados

### Incidencias (Solo Usuario Maestro)
- Registrar incidencias (faltas, retardos, permisos)
- Seguimiento de estado de incidencias
- Historial de incidencias por empleado

### Reportes (Solo Usuario Maestro)
- Reportes de asistencia por período
- Reportes por empleado
- Reportes por departamento

### Cumpleaños (Solo Usuario Maestro)
- Visualización de cumpleaños del mes
- Alertas de cumpleaños del día

### Historial de Operaciones (Solo Usuario Maestro)
- Registro de todas las acciones realizadas en el sistema
- Auditoría de usuarios

## Seguridad

- Contraseñas hasheadas con `password_hash()`
- Sesiones PHP para autenticación
- Validación de permisos por tipo de usuario
- Protección contra SQL Injection con `real_escape_string`
- Eliminación lógica de registros

## Soporte

Para soporte técnico o consultas, contactar al administrador del sistema.

## Licencia

Sistema desarrollado para uso exclusivo del SENIAT - Sede Nirgua.
