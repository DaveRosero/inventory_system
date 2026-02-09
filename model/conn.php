<?php
function database()
{
    $host = "localhost";
    $user = "root";
    $pw = "";
    $db = "inventory_system";

    $mysqli = new mysqli($host, $user, $pw, $db);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8");

    return $mysqli;
}
?>