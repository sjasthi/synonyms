<?php
    require('db_configuration.php');

    $email = $_POST['email'];
    $userName = $_POST['username'];
    $password = $_POST['password'];
    //echo $email.", ".$userName.", ".$password.'<br>';

    $exists = checkUserExistence($userName, $db);
    if($exists == true){
        //echo $exists;
        echo 'User already exists<br>';
        echo '<a href="register.php">Return to Register</a>';
        
    }else{
        //echo $exists;
        echo 'does not exist<br>';
        insertIntoDB($userName, $password, $email, $db);
        echo 'added<br>';
        
    }

    function checkUserExistence($userName, $db){
        $query = "SELECT * FROM user WHERE userName = '".$userName."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $numOfRows = $stmt->num_rows;
        if($numOfRows > 0){
            //echo 'true<br>';
            $stmt->free_result();
            $stmt->close();
            return true;
        }else{
            //echo 'false<br>';
            $stmt->free_result();
            $stmt->close();
            return false;
        }
    }

    function insertIntoDB($userName, $password, $email, $db){
        $query = "INSERT INTO user(userName, password, email, isAdmin) VALUES('".$userName."', '".$password."', '".$email."', 0)";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->close();
    }