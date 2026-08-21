<?php
$host="localhost";
$username="root";
$password="";
$database="zero_hunger";
$conn=new mysqli($host,$username,$password,$database);
if($conn->connect_error){
    die("Failed: ". $conn->connect_error);}
?>