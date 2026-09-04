<?php
require_once "DBconnect.php";
$name = $_POST["name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$location = $_POST["location"];
$sql = "INSERT INTO Donor
        (name, phone, email, location)
        VALUES
        ('$name', '$phone', '$email', '$location')";

if ($conn->query($sql))
{
    header("Location: showDonors.php");
    exit;
}
else
{
    echo "Error: " . $conn->error;
}

$conn->close();

?>