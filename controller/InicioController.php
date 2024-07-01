<?php

class InicioController{
    private $presenter;
    private $model;

    public function __construct($presenter, $model){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        if (isset($_GET['timeout']) && $_GET['timeout'] == 'true')
            unset($_SESSION['preguntaID']);

        $data = $this->model->obtenerDatosUsuario();
        $rol = $this->model->obtenerRol();

        $data['es_editor'] = ($rol === "EDITOR");
        $data['es_user'] = ($rol === "usuario");
        $data['es_admin'] = ($rol === "ADMIN");

        if(isset($data['es_admin'])){
            if(isset($_GET['filter'])){
            $data['grafico1'] = $this->model->obtenerPartidaPorFecha($_GET['filter']);
            }else{
                $data['grafico1'] = $this->model->obtenerPartidaPorFecha(null);
            }
            $data['grafico2'] = $this->model->obtenerUsuariosPorPais();
            $data['grafico3'] = $this->model->obtenerUsuariosPorSexo();
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // Devolver la respuesta en formato JSON
            header('Content-Type: application/json');
            echo json_encode(["data" => $data]);
            exit;  // Terminar el script después de enviar la respuesta
        } else {
            // Renderizar la vista
            $this->presenter->render("views/inicio.mustache", ["data" => $data]);
        }
    }

}
