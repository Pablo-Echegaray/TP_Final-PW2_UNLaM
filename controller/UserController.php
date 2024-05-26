<?php
class UserController
{
    private $presenter;

    public function __construct($presenter)
    {
        $this->presenter = $presenter;
    }

    public function register()
    {
        $this->presenter->render("view/registroView.mustache");
    }

    public function login()
    {
        $this->presenter->render("view/inicioSesion.mustache");
    }

    public function play()
    {
        echo "Usuario iniciado, ya puedes jugar!";
    }
}