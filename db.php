<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Newhotel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
