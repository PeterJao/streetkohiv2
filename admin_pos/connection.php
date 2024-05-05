<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $database1 = 'streetkohi';
    $database2 = 'pos_system';


    //Connecting to database
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database1", $username, $password);

        $conn-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\Exception $e) {
        $error_message = $e->getMessage();
    }

    //Make the connection variable global
    $GLOBALS['conn'] = $conn;


    //Connecting to database
    try {
        $conn2 = new PDO("mysql:host=$servername;dbname=$database2", $username, $password);

        $conn2-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\Exception $e) {
        $error_message = $e->getMessage();
    }

    //Make the connection variable global
    $GLOBALS['conn_pos'] = $conn2;
?>