<?php

class PerfilController{
    private $presenter;
    private $model;
    public function __construct($presenter, $model){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get(){
        if(isset($_GET['usuario'])){
            $idUsuario = $_GET['usuario'];
            $datosPerfil = $this->model->getPerfil($idUsuario);
            $this->presenter->render("views/perfil.mustache", ["perfilOtro" => $datosPerfil]);
        } else{
            if (!isset($_SESSION['idUsuario'])) {
                header('Location: /login');
                exit();
            }
            $idUsuario = $_SESSION['idUsuario'];
            if (empty($idUsuario)) {
                header('Location: /login');
                exit();
            }
            $datosPerfil = $this->model->getPerfil($idUsuario);
            if ($datosPerfil === null) {
                header('Location: /registrar');
                exit();
            }
            $this->presenter->render("views/perfil.mustache", ["perfil" => $datosPerfil]);
        }


    }


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
