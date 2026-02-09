<?php
require_once '../model/conn.php';

$conn = database();
if ($conn) {
    echo "Database conencted.";
}

?>