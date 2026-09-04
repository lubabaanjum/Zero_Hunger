<?php 
 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "zero_hunger"; 
 
$conn = new mysqli( 
    $servername, 
    $username, 
    $password, 
    $dbname, 
    null,
    "/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock"
); 
 
if ($conn->connect_error) { 
    die("Database connection failed: " . $conn->connect_error); 
} 
 
?>