<?php

class PerfilController{

    private $presenter;
    public function __construct($presenter){
        $this->presenter = $presenter;
    }

    public function get(){
        $this->presenter->render("views/perfil.mustache");
    }

    // METODOS POSIBLES
//    public function guardarPerfil(){
//        $nombre = $_POST["nombre"];
//        $apellido = $_POST["apellido"];
//        $correo = $_POST["correo"];
//        $telefono = $_POST["telefono"];
//        $direccion = $_POST["direccion"];
//        $perfil = new PerfilModel();
//        $perfil->setPerfil($nombre, $apellido, $correo, $telefono, $direccion);
//        $this->perfil();
//    }

//    public function eliminarPerfil($id){
//        $perfil = new PerfilModel();
//        $perfil->deletePerfil($id);
//        $this->perfil();
//    }

//    public function actualizarPerfil($id){
//        $perfil = new PerfilModel();
//        $data["perfil"] = $perfil->getPerfilId($id);
//        require_once "view/header.php";
//        require_once "view/actualizarPerfil.php";
//        require_once "view/footer.php";
//    }

//    public function actualizar(){
//        $id = $_POST["id"];
//        $nombre = $_POST["nombre"];
//        $apellido = $_POST["apellido"];
//        $correo = $_POST["correo"];
//        $telefono = $_POST["telefono"];
//        $direccion = $_POST["direccion"];
//        $perfil = new PerfilModel();
//        $perfil->updatePerfil($id, $nombre, $apellido, $correo, $telefono, $direccion);
//        $this->perfil();
//    }

}
