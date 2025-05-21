<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$database = "class_management"; 
$port = 3308; 

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

?>
