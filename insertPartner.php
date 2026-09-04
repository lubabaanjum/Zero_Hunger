<?php

require_once "DBconnect.php";

$organization_name = $_POST["organization_name"];
$contact_person = $_POST["contact_person"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$location = $_POST["location"];

$sql = "INSERT INTO Partner_organization
        (phone, email, location, organization_name, contact_person)
        VALUES ('$phone', '$email', '$location',
                '$organization_name', '$contact_person')";

if ($conn->query($sql)) {

    echo "Partner organization added successfully!";
    echo "<br><br>";
    echo "<a href='showPartners.php'>View Partner Organizations</a>";

}
else {

    echo "Error: " . $conn->error;

}

$conn->close();

?>