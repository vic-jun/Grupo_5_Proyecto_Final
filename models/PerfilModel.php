<?php

class PerfilModel{

    private $baseDeDatos;
    public function __construct($db){
        $this->baseDeDatos = $db;
    }

    public function getPerfil($id){
        $sql = "SELECT * FROM usuario WHERE id = $id";
        $stmt = $this->baseDeDatos->prepare($sql);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->baseDeDatos->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $perfil = $result->fetch_assoc();
        $stmt->close();
        return $perfil ?: null;
    }

//    public function updatePerfil($id, $nombre, $apellido, $correo, $telefono, $direccion){
//        $query = $this->baseDeDatos->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, correo = ?, telefono = ?, direccion = ? WHERE id = ?");
//        $query->execute([$nombre, $apellido, $correo, $telefono, $direccion, $id]);
//    }
//
//    public function deletePerfil($id){
//        $query = $this->baseDeDatos->prepare("DELETE FROM usuarios WHERE id = ?");
//        $query->execute([$id]);
//    }
//
//    public function setPerfil($nombre, $apellido, $correo, $telefono, $direccion){
//        $query = $this->baseDeDatos->prepare("INSERT INTO usuarios (nombre, apellido, correo, telefono, direccion) VALUES (?, ?, ?, ?, ?)");
//        $query->execute([$nombre, $apellido, $correo, $telefono, $direccion]);
//    }
//
//    public function getPerfilId($id){
//        $query = $this->baseDeDatos->prepare("SELECT * FROM usuarios WHERE id = ?");
//        $query->execute([$id]);
//        return $query->fetch(PDO::FETCH_ASSOC);
//    }

}
