<?php

class DataConversion
{
    public function deleteSpecialCharacters($str) {
        // Eliminar signos de interrogación
        $str = str_replace(array('¿', '?'), '', $str);

        // Eliminar tildes y convertirlas a su equivalente sin tilde
        $str = strtr($str,
            array(
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U'
            )
        );

        return $str;
    }
}