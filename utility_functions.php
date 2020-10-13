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

/**
 * Returns a string that contains html to create a script alert
 * @param  string [$message = -1] error message to display
 * @return string html for alert messaage
 */
function display_error($message = -1)
{
    $string = "";
    if ($message == -1) {
        $string = "<script>alert('invalid input try again');</script>";
    } else {
        $string = "<script>alert('" . $message . "');</script>";
    }
    return $string;
}

/**
 *
 * @param string $data you want to test for sqlinject
 */
function containsSql($data)
{
    // NOTE: we should just use real_escape_string(); and  prepared statements.
    $flag = false;
    $encoding = 'UTF-8';
    $dataLower = mb_strtolower($data, $encoding);
    $length = mb_strlen($dataLower, $encoding);
    if (mb_strpos($dataLower, "drop", 0, $encoding) || mb_strpos($dataLower, "show", 0, $encoding)) { // words contains drop or show
        if (mb_strpos($dataLower, "table", 0, $encoding) || mb_strpos($dataLower, "database", 0, $encoding) || containsTableNames($dataLower)) {
            return true;
        }
    }
    if (mb_strpos($dataLower, "select", 0, $encoding) || mb_strpos($dataLower, "delete", 0, $encoding) || mb_strpos($dataLower, "insert", 0, $encoding) || mb_strpos($dataLower, "update", 0, $encoding)) {
        if (mb_strpos($dataLower, "join", 0, $encoding) || containsTableNames($dataLower) || mb_strpos($dataLower, "into", 0, $encoding)) {
            return true;
        }
    }
    return $flag;
}

/**
 *
 * @param  [[Type]] $input [[Description]]
 * @return boolean  [[Description]]
 */
function containsTableNames($input)
{
    $encoding = 'UTF-8';
    $names = array("puzzles", "words", "puzzle_words", "characters", "users");
    foreach ($names as $name) {
        if (mb_strpos($input, $name, 0, $encoding)) {
            return true;
        }
    }
    return false;
}

?>