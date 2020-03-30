<?php
require_once "db.php";
function connect()
{
    $host = HOST;
    $user = USER;
    $password = PASS;
    $db = DB;
    $pass = true;
    $conn = null;

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    } catch (Exception $e) {
        $pass = false;
        echo $e->getMessage();
    }

    if($pass) {
        echo "<br><strong><font size = 5>CONNECTED TO DB $db</font></strong><br><br><br>";
        return $conn;
    }

    return null;
}
