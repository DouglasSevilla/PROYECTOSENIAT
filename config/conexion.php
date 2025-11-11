<?php
class Conexion {
    private $host = "localhost";
    private $usuario = "root";
    private $clave = "";
    private $base_datos = "proyectoseniat";
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
            // No imprimir errores directamente (rompería JSON). Lanzar excepción para que el caller la maneje.
            throw new Exception('Error de conexión a la base de datos: ' . $e->getMessage());
        }
        
        return $this->conexion;
    }
}
?>
