<?php
require_once('db_configuration.php');
// Gets the words id of the words being used for the given puzzle_id at the given index of position_in_name.
function getWordId($puzzleId, $position_in_name)
{
    $sql = "SELECT * FROM puzzle_words WHERE puzzle_id = '$puzzleId' AND position_in_name = '$position_in_name';";
    $result = run_sql($sql);
    $num_rows = $result->num_rows;

    if ($num_rows > 0) {
        if ($num_rows == 1) {
            // should almost always land here
            $row = $result->fetch_assoc();
            return $row["word_id"];
        } else {
            // shouldn't happen -- should be unique composite key between puzzle_id and position_in_name
        }
    } else {
        return null; // No word_id with this combination of puzzle_id and position_in_name (index of words in the puzzle_words).
    }
}

// returns an array of the indexes of the desired character value in the words with the desired word_id
function getCharIndex($word_id, $char_val)
{
    $sql = "SELECT * FROM characters WHERE word_id = '$word_id' AND character_value =  '$char_val';";
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        // should almost always land here
        $indexes = array();
        while ($row = $result->fetch_assoc()) {
            array_push($indexes, $row["character_index"]);
        }
        return $indexes;
    } else {
        return null; // flow of control shouldn't go here for the most part
    }
}

// Gets the words id of the given words from the words table
function getWordIdFromWord($word)
{
    // Get words if from words table
    $sql = 'SELECT word_id FROM words WHERE word =\'' . $word . '\';';
    $result = run_sql($sql);
    // $num_rows = $result-> // finish check for num rows == 0 and > 1
    $row = $result->fetch_assoc();
    $word_id = $row["word_id"];
    return $word_id;
}

// Gets list of characters in contains in the words with the given words id
function getCharactersForWordId($word_id)
{
    // get character list for the given words
    $sql = 'SELECT * FROM characters WHERE word_id = \'' . $word_id . '\' ORDER BY character_index;';
    //echo $sql;
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            array_push($rows, $row['character_value']);
        }
        return $rows;
    } else {
        return null; // flow of control shouldn't go here for the most part
    }
}

// Gets list of characters in contains in the given words
function getCharactersForWord($word)
{
    //get words if of the given words
    return getWordChars($word);
}

// returns the word_id of param or the max word_id if no param provided
function getMaxWordId($index = -1)
{
    if ($index == -1) {
        $sql = 'SELECT MAX(word_id) AS Count FROM words;';
        $result = run_sql($sql);
        $row = $result->fetch_assoc();
        $count = $row["Count"];
        return ($count + 1);
    } else {
        $sql = 'SELECT word_id FROM words WHERE word =\'' . $index . '\';';
        $result = run_sql($sql);
        $row = $result->fetch_assoc();
        $word_id = $row["word_id"];
        return ($word_id);
    }
}

// returns the puzzle_id of param or the max puzzle_id if no param provided
function getMaxPuzzleId($index = -1)
{
    if ($index == -1) {
        $sql = 'SELECT MAX(puzzle_id) AS Count FROM puzzles;';
        $result = run_sql($sql);
        $row = $result->fetch_assoc();
        $count = $row["Count"];
        return ($count + 1);
    } else {
        $sql = 'SELECT puzzle_id FROM puzzles WHERE puzzle_name =\'' . $index . '\';';
        $result = run_sql($sql);
        $row = $result->fetch_assoc();
        $puzzle_id = $row["puzzle_id"];
        return ($puzzle_id);
    }
}

// returns the words value of the desired word_id
function getWordValue($word_id)
{
    $sql = 'SELECT * FROM words WHERE word_id = \'' . $word_id . '\';';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;

    if ($num_rows == 1) {
        // should almost always land here
        $row = $result->fetch_assoc();
        return $row["word"];
    } else if ($num_rows == 0) {
        return null; // word_id doesn't exist
    } else {
        // There is more than one words with this word_id (should be impossible because of the primary key on word_id)
    }
}

// returns the english_word of the word_id given in the parameter.
function getClueWord($word_id)
{
    $sql = 'SELECT * FROM words WHERE word_id = \'' . $word_id . '';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        if ($num_rows == 1) {
            // should almost always land here
            $row = $result->fetch_assoc();
            return $row["english_name"];
        } else {
            // select a different clue words if given words id is same as repid
            while ($row = $result->fetch_assoc()) {
                if ($word_id != $row["word_id"]) {
                    return $row["english_name"];
                }
            }
        }
    } else {
        return null; // flow of control shouldn't go here for the most part, because it would mean the word_id
        // doesn't have a rep_id (referential integrity violation) or the word_id doesn't exist
    }
}


function getClue($word_id)
{
    $sqlStatement = 'SELECT * FROM words WHERE word_id=\'' . $word_id . '\';';
    $result = run_sql($sqlStatement);
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["english_word"];
    }
    return null;
}

function getWordIdFromChar($char, $preferedPosition, $minLength, $maxLength)
{
    $result;
    $num_rows;
    if ($preferedPosition !== -1) {
        ///echo $char;
        $sqlStatement = 'SELECT * FROM characters JOIN words ON words.word_id = characters.word_id WHERE (characters.character_value = \'' . $char . '\') AND (characters.character_index=\'' . $preferedPosition . '\') AND (LENGTH(words.word) > ' . $minLength . ') AND (LENGTH(words.word) < ' . $maxLength . ')';
        $result = run_sql($sqlStatement);
        $num_rows = $result->num_rows;
        if ($num_rows <= 0) {
            $preferedPosition = -1;
        }
    }
    if ($preferedPosition == -1) {
        $sqlStatement = 'SELECT * FROM characters JOIN words ON words.word_id = characters.word_id WHERE (characters.character_value = \'' . $char . '\') AND (LENGTH(words.word) > ' . $minLength . ') AND (LENGTH(words.word) < ' . $maxLength . ')';
        $result = run_sql($sqlStatement);
        $num_rows = $result->num_rows;
    }
    // echo $num_rows;
    if ($num_rows > 0) {
        $index = 0;
        $randomNumber = mt_rand(0, $num_rows - 1);
        while ($row = $result->fetch_assoc()) {
            if ($index === $randomNumber) {
                // echo $row['word_value'];
                return $row["word_id"];
            }
            $index++;
        }

    }
    return false;
}

// adds a random puzzle_word for the puzzle with name puzzle_name for the character at
// the index in the puzzle_name. $puzzlewords are the existing puzzle_words for the puzzle.
// Returns the word_id of the words added to puzzle_words. Returns null if no words could be
// added to the puzzle_words for this puzzle.
function getRandomWord($character, $puzzleWords)
{
    $sql = 'SELECT * FROM characters  WHERE (letter = \'' . $character . '\')';
    $result = run_sql($sql);
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        array_push($rows, $row);
    }
    $chosen_word = null;
    $numRows = count($rows);
    //echo $character;
    //var_dump($puzzleWords);

    while (true) {
        //echo 'Rows count: ' . $numRows . ' <br>';
        if ($numRows < 1) {
            break;
        } elseif ($numRows > 1) {
            $random = rand(0, $numRows - 1);
        } elseif ($numRows === 1) {
            $random = 0;
        }
        //echo $random;
        try {
            $word = $rows[$random]["PuzzleWord"];
            if (!in_array($word, $puzzleWords, true)) {
                $chosen_word = $rows[$random];
                break;
            } else {
                $numRows--;
                array_splice($rows, $random, 1);
            }
        } catch (Exception $e) {
            // try again
            $numRows--;
        }
    }
    return $chosen_word;
}


function getRandomClueWord($word_id)
{
    $sqlStatement = 'SELECT * FROM words WHERE word_id= ' . $word_id . '';
    $result = run_sql($sqlStatement);
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        $index = 0;
        $randomNumber = mt_rand(0, $num_rows - 1);
        while ($row = $result->fetch_assoc()) {
            if ($index === $randomNumber) {
                return $row["english_word"];
            }
            $index++;
        }
    }
    return false;
}

function getClueFromWord($word_value)
{
    $sqlStatement = 'SELECT * FROM words WHERE word=' . $word_value . '';
    $result = run_sql($sqlStatement);
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        $index = 0;
        $randomNumber = mt_rand(0, $num_rows - 1);
        while ($row = $result->fetch_assoc()) {
            if ($index === $randomNumber) {
                return $row["english_word"];
            }
            $index++;
        }
    }
    return false;
}



function checkPuzzleId($puzzle_id)
{
    $sql = 'SELECT * FROM puzzles WHERE puzzle_id=\'' . $puzzle_id . '\';';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    if ($num_rows > 0) {
        return true;
    }
    return false;
}

function getWordFromPuzzleWords($puzzle_id)
{
    $sql = 'SELECT * FROM puzzle_words WHERE puzzle_words.puzzle_id=\'' . $puzzle_id . '\' ORDER BY position_in_name;';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    $wordList = array();
    if ($num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Check if word exists in the word table
            $wordInfo = array();
            $word_id = "";
            $word = $row['word'];
            $clue = $row['clue'];
            $image = $row['image'];
            $sql = 'SELECT * FROM words WHERE word = \'' . $word . '\';';
            $res = run_sql($sql);
            if ($res->num_rows !== 0) {
                $word_row = $res->fetch_assoc();
                $clue = $word_row['english_word'];
                $word_id = $word_row['word_id'];
                $image = $word_row['image'];
            } else {
                if (count($word) > 1) {
                    $sql = 'INSERT INTO words (word_id, word, english_word, image) VALUES (DEFAULT, \'' . $word . '\', \'' . $clue . '\',  \'\');';
                    $word_id = run_sql($sql);
                }
            }
            array_push($wordInfo, $word_id, $word, $clue, $image);
            array_push($wordList, $wordInfo);
        }
    }
    return $wordList;
}

function getWordValuesFromPuzzleWords($puzzle_id)
{
    $sql = 'SELECT word FROM puzzle_words WHERE puzzle_words.puzzle_id=\'' . $puzzle_id . '\' ORDER BY position_in_name;';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    $words = array();
    if ($num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($words, $row["word"]);
            $word = $row["word"];
            // Check if word exists in the word table
            $sql = 'SELECT * FROM words WHERE word = \'' . $word . '\';';
            try {
                run_sql($sql);
            } catch (Exception $e) {
                $sql = 'INSERT INTO words (word_id, word, english_word, image) VALUES (DEFAULT, \'' . $word . '\', \'' . $row['english_word'] . '\',  \'' . $row['english_word'] . '.jpg\');';
                run_sql($sql);
            }
        }
        return $words;
    }
    return false;
}

function getClueValuesFromPuzzleWords($puzzle_id)
{
    $sql = 'SELECT clue FROM puzzle_words WHERE puzzle_words.puzzle_id=\'' . $puzzle_id . '\' ORDER BY puzzle_words.position_in_name;';
    $result = run_sql($sql);
    $num_rows = $result->num_rows;
    $words = array();
    if ($num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($words, $row["clue"]);
        }
        //var_dump($words);
        return $words;
    }
    return false;
}

function checkWord($word)
{
    $sqlStatement = 'SELECT * FROM words WHERE word=\'' . $word . '\';';
    $result = run_sql($sqlStatement);
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        array_push($rows, $row);
    }
    $num_rows = count($rows);
    if ($num_rows !== 0) {
        return $rows[0]["word"];
    }
    return null;
}




?>
