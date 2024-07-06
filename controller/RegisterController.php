<?php
class RegisterController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get() {

        $this->presenter->render("view/registrarseView.mustache", ["listMaps" => $this->getMaps()]);
        //$this->presenter->render("view/registrarseView.mustache");
    }

    public function add() {
        $nombre = $_POST["nombre"] ?? "";
        $apellido = $_POST["apellido"] ?? "";
        $nacimiento = $_POST["nacimiento"];
        $sexo = $_POST["sexo"] ?? "";
        $pais = $_POST["pais"] ?? "";
        $ciudad = $_POST["ciudad"] ?? "";
        $email = $_POST["mail"] ?? "";
        $contrasena = $this->model->validarContrasena($_POST["password"], $_POST["repeat_password"]);
        $usuario = $this->model->validarUsuario($_POST["username"]);
        $foto = $this->model->verificarImagen($_FILES["perfil"]);
        $lat = $_POST["lat"] ?? "";
        $lng = $_POST["lng"] ?? "";

        $data = $this->model->addUser($nombre, $apellido, $nacimiento, $sexo, $ciudad, $pais, $email, $contrasena, $usuario, $foto, $lat, $lng);
        $this->presenter->render("view/".$data[0]."View.mustache", $data[1]);
    }

    private function getMaps(){
        $listMaps = $this->model->getMaps();
        //echo $listMaps[0][0];
        return $listMaps;
    }
}