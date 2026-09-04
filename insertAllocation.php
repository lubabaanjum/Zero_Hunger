<?php

require_once "DBconnect.php";

$quantity = $_POST["quantity"];
$date = $_POST["date"];
$donation_id = $_POST["donation_id"];
$recipient_id = $_POST["recipient_id"];

$sql = "INSERT INTO Resource_Allocation
        (quantity, date, donation_id, recipient_id)
        VALUES ('$quantity', '$date', '$donation_id', '$recipient_id')";

if ($conn->query($sql)) {

    echo "Resource allocation added successfully!";
    echo "<br><br>";
    echo "<a href='showAllocations.php'>View Resource Allocations</a>";

}
else {

    echo "Error: " . $conn->error;

}

$conn->close();

?>