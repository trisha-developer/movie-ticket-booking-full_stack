<?php

$server_name = "localhost";
$user = "root";
$pass = "";
$db = "cinebook";

$conn = mysqli_connect($server_name, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed");
}

?>