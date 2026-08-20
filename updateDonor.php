<?php
require_once "DBconnect.php";
$id=$_POST["donor_id"];
$name=$_POST["name"];
$phone=$_POST["phone"];
$email=$_POST["email"];
$zip=$_POST["zip"];
$ward=$_POST["ward"];
$postal_card=$_POST["postal_card"];
$sql="UPDATE Donor SET name = ?,phone = ?,email = ?,zip = ?,ward = ?,postal_card = ? WHERE donor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssi",$name,$phone,$email,$zip,$ward,$postal_card,$id
);
if ($stmt->execute()) {
    echo "Donor updated successfully!";
    echo "<br><br>";
    echo "<a href='showdonors.php'>View Donors</a>";
} 
else{
    echo "Error: " . $stmt->error;}
$stmt->close();
$conn->close();
?>