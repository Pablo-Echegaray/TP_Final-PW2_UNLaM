<?php
class RegisterModel
{
    private $database;
    private $mailer;
    private $maps;

    public function __construct($database, $mailer, $maps)
    {
        $this->database = $database;
        $this->mailer = $mailer;
        $this->maps = $maps;
    }

    public function agregar($nombre, $apellido, $nacimiento, $sexo, $ciudad, $pais, $email, $contrasena, $usuario, $foto)
    {
        $this->database->execute("
            INSERT INTO
            usuarios
            (nombre, apellido, year_birth, sexo, ciudad, pais, email, password, nombre_usuario, foto, rol, activo, qr, entregadas, hit)
            VALUES
            ('$nombre','$apellido','$nacimiento','$sexo','$ciudad','$pais', '$email','$contrasena','$usuario','$foto', 'J', 0, 'QR', 100, 50)
        ");
    }

    public function enviarCorreoVerificacion($email, $nombre, $usuario)
    {
        $codigoVerificacion = "ABC".rand("100", "999");
        $this->guardarCodigoVerificacion($usuario, $codigoVerificacion);

        try{
            $this->mailer->setFrom('preguntados.ejemplo@info.com', 'Preguntados');
            $this->mailer->addAddress($email, $nombre);
            $this->mailer->addReplyTo('preguntados.ejemplo@info.com', 'Preguntados');

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Verifica tu mail para Jugar!';
            $this->mailer->Body =
                '<b>¡Verificacion!<b> <br>
                    <h1>Tu codigo es: '.$codigoVerificacion.'</h1><br>
                Para jugar, haz click en el siguiente link y copia tu codigo: <br>
                http://localhost/TP_Final-PW2_UNLaM/user/validation';

            $this->mailer->send();
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado: {$this->mailer->ErrorInfo}";
        }
    }


    public function verificarImagen($imagen)
    {
        if (isset($imagen)) {
            $targetDir = 'public/image/';
            $targetFile = $targetDir . basename($imagen['name']);
            if (move_uploaded_file($imagen['tmp_name'], $targetFile)) {
                return $imagen["name"];
            }
        }
        return "perfil_sin_foto.jpg";
    }

    public function validarNacimiento($nacimiento)
    {
        if (isset($nacimiento)) {
            return $this->calcularEdad($nacimiento);
        }
        return "";
    }

    public function validarContrasena($contrasena, $contrasenaRepetida)
    {
        if (isset($contrasena) && isset($contrasenaRepetida)){
            if ($this->verificarContrasena($contrasena, $contrasenaRepetida)){
                return $contrasena;
            } else{ return null; }
        }
        return null;
    }

    public function validarUsuario($username)
    {
        if (isset($username)) {
            if ($this->verificarUsername($username)) {
                return $username;
            } else { return null; }
        }
        return null;
    }

    public function addUser($nombre, $apellido, $nacimiento, $sexo, $ciudad, $pais, $email, $contrasena, $usuario, $foto, $lat, $lng){
        $data = [];
        if ($contrasena != null) {
            if ($usuario != null) {
                //AGREGA AL USUARIO COMO NO ACTIVO y POR DEFECTO COMO JUGADOR
                $this->agregar($nombre, $apellido, $nacimiento, $sexo, $ciudad, $pais, $email, $contrasena, $usuario, $foto);
                $this->saveCoordinates($usuario, $ciudad, $pais, $lat, $lng);
                $this->enviarCorreoVerificacion($email, $nombre, $usuario);

                $mensajeVerificacion = "Verifica tu correo para iniciar sesion";
                array_push($data, "iniciarSesion", ["mensaje" => $mensajeVerificacion]);
                //$this->render("view/iniciarSesionView.mustache", ["mensaje" => $mensajeVerificacion]);

            } else {
                $error = "El nombre de usuario ya existe";
                array_push($data, "registrarse", ["error" => $error]);
                //$this->render("view/registrarseView.mustache", ["error" => $error]);
            }
        } else {
            $error = "Las contraseñas no coinciden";
            array_push($data, "registrarse", ["error" => $error]);
            //$this->render("view/registrarseView.mustache", ["error" => $error]);
        }
        return $data;
    }
    public function getMaps(){
        $coll = $this->maps->getMarkersBlankLatLng();
        //echo $coll;
        $coll = json_encode($coll, true);
        //echo $coll;
        $allData = $this->maps->getMarkers();
        //echo $allData;
        $allData = json_encode($allData, true);
        echo "<div id='echo_supremo'>" . $allData . "</div>";
        return array("coll"=>$coll, "allData"=>$allData);
    }


    public function saveCoordinates($user, $city, $country, $lat, $long){
        $user_id = $this->database->query("
            SELECT *
            FROM usuarios
            WHERE nombre_usuario = '$user' ");
        $this->maps->saveCoordinates($user_id[0]['id'], $city, $country, $lat, $long);
    }


    private function calcularEdad($nacimiento)
    {
        $anio_actual = date("Y");
        return $anio_actual - $nacimiento;
    }

    private function verificarContrasena($contrasena, $contrasenaRepetida)
    {
        if ($contrasena == $contrasenaRepetida){
            return true;
        }
        return false;
    }

    private function verificarUsername($username)
    {
        $existe = $this->database->query("
            SELECT *
            FROM usuarios
            WHERE nombre_usuario = '$username' ");
        if (!$existe){
            return true;
        }
        return false;
    }

    private function capitalizeFirstLetter($string):string {
        $string = strtolower($string);
        if (str_word_count($string) > 1){
            $string = ucwords($string);
        }else{
            $string = ucfirst($string);
        }
        return $string;
    }

    private function guardarCodigoVerificacion($usuario, $codigoVerificacion)
    {
        $this->database->execute("
            UPDATE usuarios
            SET codigo_verificacion = '$codigoVerificacion'
            WHERE nombre_usuario = '$usuario';
        ");
    }
}