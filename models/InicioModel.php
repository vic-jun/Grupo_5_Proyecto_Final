<?php

class InicioModel
{
    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function obtenerDatosUsuario(){
        $usuario = $_SESSION['idUsuario'];
        $sql = "SELECT * FROM usuario WHERE id = '$usuario'";
        $res = $this->baseDeDatos->query($sql);
        return array('nombre_de_usuario' => $res[0]['nombre_de_usuario'], 'puntaje' => $res[0]['puntaje']);
    }

    public function obtenerRol()
    {
        $usuario = $_SESSION['idUsuario'];
        $sql = "SELECT rol FROM usuario WHERE id = '$usuario'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['rol'];

    }

    public function obtenerPartidaPorFecha($filter){
        $sql = "";
        if ($filter == null) {
            $filter = "week";
        }
        switch($filter) {
            case "week":
                $sql = "SELECT DATE(fecha_partida) as fecha, COUNT(*) as partidas_total 
                FROM partida 
                WHERE YEARWEEK(fecha_partida, 1) = YEARWEEK(CURDATE(), 1) 
                GROUP BY DATE(fecha_partida)";
                break;
            case "month":
                $sql = "SELECT DATE(fecha_partida) as fecha, COUNT(*) as partidas_total 
                FROM partida 
                WHERE MONTH(fecha_partida) = MONTH(CURDATE()) AND YEAR(fecha_partida) = YEAR(CURDATE()) 
                GROUP BY DATE(fecha_partida)";
                break;
            case "year":
                $sql = "SELECT DATE(fecha_partida) as fecha, COUNT(*) as partidas_total 
                FROM partida 
                WHERE YEAR(fecha_partida) = YEAR(CURDATE()) 
                GROUP BY DATE(fecha_partida)";
                break;
        }

        $res = $this->baseDeDatos->query($sql);

        // Llenar los arrays con los datos de la base de datos
        foreach ($res as $row) {
            $labels[] = $row['fecha']; // Asumiendo que 'fecha_partida' puede ser usada como etiqueta
            $values[] = $row['partidas_total']; // Asumiendo que 'puntuacion' puede ser usada como valor
        }

        // Devolver un array asociativo con las etiquetas y los valores
        return ['labels' => $labels, 'values' => $values];
    }

    public function obtenerUsuariosPorPais(){

            $sql = "SELECT pais, COUNT(id) as usuarios_total 
        FROM usuario 
        GROUP BY pais";

        $res = $this->baseDeDatos->query($sql);

            // Llenar los arrays con los datos de la base de datos
            foreach ($res as $row) {
                $labels[] = $row['pais']; // Asumiendo que 'pais' puede ser usada como etiqueta
                $values[] = $row['usuarios_total'];// Asumiendo que 'usuarios_total' puede ser usada como valor
            }

        // Devolver un array asociativo con las etiquetas y los valores
        return ['labels' => $labels, 'values' => $values];
    }

    public function obtenerUsuariosPorSexo(){

        $sql = "SELECT genero, COUNT(id) as usuarios_total 
        FROM usuario 
        GROUP BY genero";

        $res = $this->baseDeDatos->query($sql);

        // Llenar los arrays con los datos de la base de datos
        foreach ($res as $row) {
            $labels[] = $row['genero']; // Asumiendo que 'sexo' puede ser usada como etiqueta
            $values[] = $row['usuarios_total']; // Asumiendo que 'usuarios_total' puede ser usada como valor
        }

        // Devolver un array asociativo con las etiquetas y los valores
        return ['labels' => $labels, 'values' => $values];
    }

}