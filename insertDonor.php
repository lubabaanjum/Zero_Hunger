<?php
require_once "DBconnect.php";
$name=$_POST["name"];
$phone=$_POST["phone"];
$email=$_POST["email"];
$zip=$_POST["zip"];
$ward=$_POST["ward"];
$postal_card=$_POST["postal_card"];
$sql="INSERT INTO Donor (name, phone, email, zip, ward, postal_card) VALUES (?, ?, ?, ?, ?, ?)";
$stmt=$conn->prepare($sql);
$stmt->bind_param(
    "ssssss",$name,$phone,$email,$zip,$ward,$postal_card
);
if ($stmt->execute()){
    echo "Donor added successfully!";} 
else{
    echo "Error: " . $stmt->error;}
$stmt->close();
$conn->close();
?>