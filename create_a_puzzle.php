<?php
require_once('db_configuration.php');
require_once('my_sql_functions.php');
require_once('language_processor_functions.php');

// Returns true if there is one puzzle name with this puzzle_name and false if the puzzle_name doesn't exist in the puzzles table.
// Returns null if there is more than one puzzle_id with this puzzle name.
function checkName($puzzle_name)
{
    $sql = 'SELECT * FROM puzzles WHERE puzzle_name = \'' . $puzzle_name . '\';';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    if ($num_rows == 0) {
        return false;
    } else if ($num_rows >= 1) {
        return true;
    } else {
        return null;
    }
}

function getPuzzleId($puzzle_name)
{
    $sql = 'SELECT * FROM puzzles WHERE puzzle_name = \'' . $puzzle_name . '\';';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    if ($num_rows >= 1) {
        $row = $result->fetch_assoc();
        return $row["puzzle_id"];
    } else {
        return null;
    }
}

function getPuzzleName($puzzle_id)
{
    $sql = 'SELECT * FROM puzzles WHERE puzzle_id = \'' . $puzzle_id . '\';';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["puzzle_name"];
    } else {
        return null;
    }
}

function create_puzzle($name, $email = 'admin')
{
    if (!adminSessionExists()) {
        $email = 'user';
    }
    $sql = 'INSERT INTO puzzles (puzzle_id, puzzle_name, creator_email) VALUES
		(DEFAULT, \'' . $name . '\', \'' . $email . '\');';
    run_sql('SET foreign_key_checks = 0;');
    run_sql($sql);
    run_sql('SET foreign_key_checks = 1;');
}

function input_puzzle_words($puzzle_id, $word_array, $clue_array, $images)
{
    for ($i = 0; $i < count($word_array); $i++) {
        $sql = 'INSERT INTO puzzle_words (puzzle_id, position_in_name, word, clue, image) VALUES 
      (' . $puzzle_id . ', ' . $i . ', \'' . $word_array[$i] . '\', \'' . $clue_array[$i] . '\', \'' . $images[$i] . '\');';
        run_sql($sql);
    }
}

function create_puzzle_words($name)
{
    $sql = 'SELECT * FROM puzzles WHERE puzzle_name = \'' . $name . '\';';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    $num_rows = $result->num_rows;

    if ($num_rows == 0) {
        return false;
    } else {
        $row = $result->fetch_assoc();
        $puzzle_id = $row["puzzle_id"];
        $puzzle_name = $row["puzzle_name"];

        // if puzzle words is not empty, delete all puzzle words for this puzzle
        $flag = puzzle_words_empty($puzzle_id);
        if (!$flag) {
            $sql = 'DELETE FROM puzzle_words WHERE puzzle_id = \'' . $puzzle_id . '\';';
            run_sql($sql);
        }

        $parsedWord = getWordChars($puzzle_name);
        $namelen = count($parsedWord);
        $puzzlewords = [];
        for ($i = 0; $i < $namelen; $i++) {
            $word_chosen = add_puzzle_word($puzzle_id, $parsedWord[$i], $i, $puzzlewords);
            if ($word_chosen != null) {
                array_push($puzzlewords, $word_added);
            } else {
                // no words could be added. More should probably be done (some type of default action).
            }
        }
    }
}

function add_puzzle_word($puzzle_id, $character, $i, $puzzlewords)
{
    $word_chosen = get_random_word($character, $puzzlewords);
    if ($word_chosen != null) {
        array_push($puzzlewords, $word_added);
        $clue_word = getClueFromWord($word_chosen);
        $sql = 'INSERT INTO puzzle_words (puzzle_id, position_in_name, word, clue) VALUES
								(' . $puzzle_id . ',' . $i . ',' . $word_chosen . ',' . $i . ',' . $clue_word . ');';
        run_sql($sql);
        return $word_chosen;
    } else {
        // no words could be added. More should probably be done (some type of default action).
        echo 'Error -- No words could be added for puzzle ' . $puzzle_name . ' at index ' . $i . ' for character ' . $parsedWord[$i] . '.';
        return null;
    }
}

// adds a random puzzle_word for the puzzle with name puzzle_name for the character at
// the index in the puzzle_name. $puzzlewords are the existing puzzle_words for the puzzle.
// Returns the word_id of the words added to puzzle_words. Returns null if no words could be
// added to the puzzle_words for this puzzle.
function get_random_word($character, $puzzlewords)
{
    $sql = 'SELECT * FROM characters WHERE character_value = \'' . $character . '\' GROUP BY word_id;';
    $result = run_sql($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        array_push($rows, $row);
    }
    $chosen_word = null;

    while (true) {
        var_dump($puzzlewords);
        $numofRows = count($rows);
        echo 'Rows count: ' . $numofRows . ' <br>';
        $random = rand(0, $numofRows - 1);
        echo 'Random: ' . $random . ' <br>';
        $word = $rows[$random]["word"];

        if (!in_array($word, $puzzlewords, true)) {
            $chosen_word = $word;
            break;
        } else {
            unset($rows[$random]);
        }
    }
    return $chosen_word;
}

// returns true if the puzzle doesn't have any puzzle words assigned to it; false if it does.
function puzzle_words_empty($puzzle_id)
{
    $sql = 'SELECT * FROM puzzle_words WHERE puzzle_id = \'' . $puzzle_id . '\';';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;

    if ($num_rows > 0) {
        return false;
    } else {
        return true;
    }
}

// Returns array of words associated with puzzle_id in puzzle_words
function get_puzzle_words($puzzle_id)
{
    $puzzleWords = [];
    $sql = 'SELECT * FROM puzzle_words WHERE puzzle_id = ' . $puzzle_id . ' ORDER BY position_in_name;';
    $result = run_sql($sql);

    while ($row = $result->fetch_assoc()) {
        array_push($puzzleWords, $row["word"]);
    }

    return $puzzleWords;
}

// Returns array of words associated with puzzle_id in puzzle_words
function get_clue_words($puzzle_id)
{
    $puzzleWords = [];
    $sql = 'SELECT * FROM puzzle_words WHERE puzzle_id = ' . $puzzle_id . ' ORDER BY position_in_name;';
    $result = run_sql($sql);

    while ($row = $result->fetch_assoc()) {
        array_push($puzzleWords, $row["clue"]);
    }

    return $puzzleWords;
}


function getShowSolution($puzzleName)
{
    if (adminSessionExists() || sessionExists()) {
        $html = '<input class="main-buttons" type="button" name="show_solution" value="Show Solution" onclick="main_buttons(\'show\');">';
    } else {
        //$html = '<a href="login.php?puzzleName=' . $puzzleName . '"><input class="main-buttons" value="Show Solution" type="button"></a>';
        $html = '<input class="main-buttons" value="Show Solution" type="button" onclick="alert(\'You should be logged in to see the solution. Please contact the system administrator.\');">';
    }
    return $html;
}


function getCharacterIndex($word, $char)
{
    $charsInWord = getWordChars($word);
    for ($i = 0; $i < count($charsInWord); $i++) {
        if ($charsInWord[$i] == $char) {
            return $i;
        }
    }
    return -1;
}

function getImage($image_name)
{
    $not_in_db = "./Images/notInDatabase.png";
    $not_in_folder = "./Images/notInFolder.png";
    $imagePath = "./Images/" . $image_name;

    if (empty($image_name)) {
        return $not_in_db;
    } elseif (!file_exists($imagePath)) {
        return $not_in_folder;
    } else {
        return $imagePath;
    }
}

function getImageIfExists($image_name)
{
    $not_in_db = "./Images/notInDatabase.png";
    $not_in_folder = "./Images/notInFolder.png";
    $image = getImage($image_name);

    if (strcasecmp($image, $not_in_db) ===0 || strcasecmp($image, $not_in_folder) ===0) {
        return null;
    } else {
        return $image;
    }
}


?>
