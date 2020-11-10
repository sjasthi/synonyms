<?php
/**
 * Strips all slashes removes all spaces and converts html special characters into harmless input
 * @param  string $data to be sterilized
 * @return string sterilized data
 */
function validate_input($data)
{
    $data = stripslashes($data);
    // $data = preg_replace('/\s+/', '', $data);
    $data = htmlspecialchars($data);
   
    //$data = trim($data);
    $data = str_replace("'", "", $data);
    $data = str_replace(" ", "", $data);
    $data = mb_strtolower($data);

    return $data;
}

// Strips white spaces and converts the word to lower case
function validate_word($data)
{
    $data = str_replace(chr(194) . chr(160), '', $data);
    $data = str_replace(" ", "", $data);
    $data = mb_strtolower($data);
    return $data;
}

/**
 * Calles validate_input over an array
 * @param  string $array to be steralized
 * @return string steralized data
 */
function validate_array($array)
{
    $arrayLng = count($array);
    for ($i = 0; $i < $arrayLng; ++$i) {
        $array[$i] = validate_input($array[$i]);
    }
    $array = array_filter($array);
    return $array;
}

?>