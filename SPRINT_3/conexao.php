<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sprint";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection filed: " . $conn->connect_error);
}

?>