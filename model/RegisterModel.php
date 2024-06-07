<?php
class RegisterModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
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

    public function generarCodigo() { return "ABC".rand("100", "999"); }

    public function agregar($nombre, $apellido, $nacimiento, $sexo, $ciudad, $pais, $email, $contrasena, $usuario, $foto, $codigo)
    {
        //$idSexo = $this->getRowByValueOfField($sexo, "sexos", "descripcion")['id'];
        $this->database->execute(
            "INSERT INTO usuarios(nombre, apellido, year_birth, sexo, ciudad, pais, email, password, nombre_usuario, foto, qr)
             VALUES ('$nombre','$apellido','$nacimiento','$sexo','$ciudad','$pais', '$email','$contrasena','$usuario','$foto',NULL)"
        );
        //$this->setCityCountryInUser($pais, $ciudad, $usuario);
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

}