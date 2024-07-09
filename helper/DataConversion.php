<?php

class DataConversion
{
    public function deleteSpecialCharacters($str) {
        $str = str_replace(array('¿', '?'), '', $str);

        $str = strtr($str,
            array(
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U'
            )
        );
        return $str;
    }
}