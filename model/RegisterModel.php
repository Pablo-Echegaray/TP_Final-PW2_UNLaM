<?php
class RegisterModel
{
    private $database;
    private $mailer;

    public function __construct($database, $mailer)
    {
        $this->database = $database;
        $this->mailer = $mailer;
    }

    public function calcularEdad($nacimiento)
    {
        $anio_actual = date("Y");
        return $anio_actual - $nacimiento;
    }

    public function verificarContrasena($contrasena, $contrasenaRepetida)
    {
        if ($contrasena == $contrasenaRepetida){
            return true;
        }
        return false;
    }

    public function verificarUsername($username)
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

    public function agregar($nombre, $apellido, $nacimiento, $sexo, $ciudad, $pais, $email, $contrasena, $usuario, $foto)
    {
        //Rol, qr, entregadas y hit valores por defecto
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
        //POR EL MOMENTO LO VA A GUARDAR EN EL QR EL CODIGO DE VERIFICACION, hasta cambiar la tabla usuario
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

    private function capitalizeFirstLetter($string):string {
        $string = strtolower($string);
        if (str_word_count($string) > 1){
            $string = ucwords($string);
        }else{
            $string = ucfirst($string);
        }
        return $string;
    }

    /* 
    private function validateIfValueOfFieldExists($value, $table, $field):bool {
        $result =  $this->database->query_for_one("SELECT * FROM $table WHERE $field = '$value'");
        if ($result){
            return true;
        }
        else{
            return false;
        }
    }
    private function getRowByValueOfField($value, $table, $field){
        return $this->database->query_for_one("SELECT * FROM $table WHERE $field = '$value'");
    }

    private function setCityIdCountryId($country, $city):array {
        $countryId = null;
        $cityId = null;
        if ($this->validateIfValueOfFieldExists($this->capitalizeFirstLetter($country), "paises", "descripcion")){
            $countryId = $this->getRowByValueOfField($country, "paises", "descripcion")['id'];
        }else{
            $this->addNewCountry($this->capitalizeFirstLetter($country));
            $countryId = $this->getRowByValueOfField($country, "paises", "descripcion")['id'];
        }
        if ($this->validateIfValueOfFieldExists($this->capitalizeFirstLetter($city), "ciudades", "descripcion")){
            $cityId = $this->getRowByValueOfField($city, "ciudades", "descripcion")['id'];
        }else{
            $this->addNewCity($this->capitalizeFirstLetter($city));
            $cityId = $this->getRowByValueOfField($city, "ciudades", "descripcion")['id'];
        }

        return array("countryId" => $countryId, "cityId" => $cityId);
    }

    private function setCityCountryInUser($country, $city, $username){
        $places = $this->setCityIdCountryId($country, $city);
        $countryId = $places['countryId'];
        $cityId = $places['cityId'];
        if ($this->validateIfValueOfFieldExists($username, "usuarios", "nombre_usuario")){
            $userId = $this->getRowByValueOfField($username, "usuarios", "nombre_usuario")['id'];
            echo "userId: ". $userId . "cityId: ". $cityId . "countryId: ". $countryId;
            $this->database->execute("INSERT INTO usuarios_ciudades_paises (id_usuario, id_ciudad, id_pais) VALUES('$userId', '$cityId', '$countryId')");
        }
    }

    private function addNewCountry($country){
        $this->database->execute("INSERT INTO paises(descripcion) VALUES ('$country')");
    }

    private function addNewCity($city){
        $this->database->execute("INSERT INTO ciudades(descripcion) VALUES ('$city')");
    }*/
    private function guardarCodigoVerificacion($usuario, $codigoVerificacion)
    {
        $this->database->execute("
            UPDATE usuarios
            SET codigo_verificacion = '$codigoVerificacion'
            WHERE nombre_usuario = '$usuario';
        ");
    }

}