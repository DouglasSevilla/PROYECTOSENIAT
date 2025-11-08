<?php
// Plantilla de conexión - COPIA este archivo a `config/conexion.php` y añade tus credenciales locales.
class Conexion {
    private $host = "localhost";
    private $usuario = "TU_USUARIO_DB"; // p. ej. root
    private $clave = "TU_CLAVE_DB";     // p. ej. contraseña
    private $base_datos = "TU_BASE_DE_DATOS"; // p. ej. proyectoseniat
    private $conexion;
    
    public function conectar() {
        $this->conexion = null;
        
        try {
            $this->conexion = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->base_datos,
                $this->usuario,
                $this->clave
            );
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->exec("set names utf8");
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        
        return $this->conexion;
    }
}
?>
