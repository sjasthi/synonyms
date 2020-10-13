<?php
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_DATABASE', 'synonyms');
DEFINE('DATABASE_USER', 'root');
DEFINE('DATABASE_PASSWORD', '');


//DEFINE('DATABASE_HOST', 'localhost');
//DEFINE('DATABASE_DATABASE', 'ics325sp1722');
//DEFINE('DATABASE_USER', 'ics325sp1722');
//DEFINE('DATABASE_PASSWORD', '4673');

$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
if (mysqli_connect_errno()) {
   echo '<p>Error: Could not connect to database.<br/>
   Please try again later.</p>';
   echo $db->error;
   exit;
}	

/* change character set to utf8 */
if (!$db->set_charset("utf8")) {
    //printf("Error loading character set utf8: %s\n", $db->error);
    exit();
} else {
   // printf("Current character set: %s\n", $db->character_set_name());
}
?>