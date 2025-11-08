<?php
session_start();
require_once __DIR__ . '/../modelo/usuario-modelo.php';
require_once __DIR__ . '/../modelo/historial-modelo.php';

class LoginController {
    private $modeloUsuario;
    private $modeloHistorial;
    
    public function __construct() {
        $this->modeloUsuario = new Usuario();
        $this->modeloHistorial = new HistorialOperacion();
    }
    
    public function iniciarSesion($nombre_usuario, $clave) {
        $usuario = $this->modeloUsuario->validarLogin($nombre_usuario, $clave);
        
        if ($usuario) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol'];
            
            $this->modeloHistorial->registrar($usuario['id_usuario'], "Inicio de sesión");
            
            return true;
        }
        return false;
    }
    
    public function cerrarSesion() {
        if (isset($_SESSION['id_usuario'])) {
            $this->modeloHistorial->registrar($_SESSION['id_usuario'], "Cierre de sesión");
        }
        session_destroy();
        header('Location: login.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $controller = new LoginController();
        
        if ($_POST['accion'] === 'login') {
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
            
            if ($controller->iniciarSesion($usuario, $clave)) {
                header('Location: ../index.php');
                exit();
            } else {
                header('Location: ../login.php?error=1');
                exit();
            }
        } elseif ($_POST['accion'] === 'logout') {
            $controller->cerrarSesion();
        }
    }
}
?>
