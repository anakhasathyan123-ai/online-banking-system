<?php
$host = "localhost";
$user = "root";
$pass = "Anakha@2005";        
$db   = "online_banking";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed");
}
?>
