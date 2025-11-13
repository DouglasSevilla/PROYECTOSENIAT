<?php
session_start();
require_once __DIR__ . '/../modelo/usuario-modelo.php';
require_once __DIR__ . '/../modelo/historial-modelo.php';

class UsuarioController {
    private $modeloUsuario;
    private $modeloHistorial;

    public function __construct() {
        $this->modeloUsuario = new Usuario();
        $this->modeloHistorial = new HistorialOperacion();
    }

    public function crear($nombre_usuario, $clave, $rol) {
        $existente = $this->modeloUsuario->obtenerPorNombre($nombre_usuario);
        if ($existente) return 'ERROR|El usuario ya existe';

        $resultado = $this->modeloUsuario->crear($nombre_usuario, $clave, $rol);
        if ($resultado) {
            $this->modeloHistorial->registrar($_SESSION['id_usuario'], "Creación de usuario: $nombre_usuario", "usuarios", null);
            return 'OK|Usuario creado correctamente';
        }
        return 'ERROR|Error al crear usuario';
    }

    public function actualizar($id, $nombre_usuario, $rol) {
        $resultado = $this->modeloUsuario->actualizar($id, $nombre_usuario, $rol);
        if ($resultado) {
            $this->modeloHistorial->registrar($_SESSION['id_usuario'], "Actualización de usuario: $nombre_usuario", "usuarios", $id);
            return 'OK|Usuario actualizado correctamente';
        }
        return 'ERROR|Error al actualizar usuario';
    }

    public function cambiarClave($id, $clave_actual, $clave_nueva) {
        $usuario = $this->modeloUsuario->obtenerPorId($id);
        if (!password_verify($clave_actual, $usuario['clave_hash'])) {
            return 'ERROR|La contraseña actual es incorrecta';
        }

        $resultado = $this->modeloUsuario->cambiarClave($id, $clave_nueva);
        return $resultado ? 'OK|Contraseña cambiada correctamente' : 'ERROR|Error al cambiar contraseña';
    }

    public function eliminar($id) {
        $usuario = $this->modeloUsuario->obtenerPorId($id);
        $resultado = $this->modeloUsuario->eliminar($id);
        if ($resultado) {
            $this->modeloHistorial->registrar($_SESSION['id_usuario'], "Eliminación de usuario: " . $usuario['nombre_usuario'], "usuarios", $id);
            return 'OK|Usuario eliminado correctamente';
        }
        return 'ERROR|Error al eliminar usuario';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtener' && isset($_GET['id'])) {
    try {
        $controller = new UsuarioController();
        $usuario = $controller->modeloUsuario->obtenerPorId($_GET['id']);
        foreach ($usuario as $key => $value) {
            echo "<input type='hidden' id='usr_$key' value='" . htmlspecialchars($value) . "'>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $controller = new UsuarioController();
    try {
        switch ($_POST['accion']) {
            case 'crear':
                echo $controller->crear($_POST['nombre_usuario'] ?? '', $_POST['clave'] ?? '', $_POST['rol'] ?? 'Encargado');
                break;
            case 'actualizar':
                echo $controller->actualizar($_POST['id_usuario'] ?? '', $_POST['nombre_usuario'] ?? '', $_POST['rol'] ?? 'Encargado');
                break;
            case 'eliminar':
                echo $controller->eliminar($_POST['id_usuario'] ?? '');
                break;
            case 'cambiar_clave':
                echo $controller->cambiarClave($_POST['id_usuario'] ?? '', $_POST['clave_actual'] ?? '', $_POST['clave_nueva'] ?? '');
                break;
            default:
                echo 'ERROR|Acción no reconocida';
        }
    } catch (Exception $e) {
        echo 'ERROR|' . $e->getMessage();
    }
    exit();
}
?>