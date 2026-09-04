<?php

require_once "DBconnect.php";

if (isset($_POST["allocation_id"])) {

    $allocation_id = $_POST["allocation_id"];
    $quantity = $_POST["quantity"];
    $date = $_POST["date"];
    $donation_id = $_POST["donation_id"];
    $recipient_id = $_POST["recipient_id"];


    $sql = "UPDATE Resource_Allocation SET quantity = '$quantity',date = '$date',donation_id = '$donation_id',recipient_id = '$recipient_id'
    WHERE allocation_id = '$allocation_id'";


    if ($conn->query($sql)) {

        echo "Resource allocation updated successfully!";
        echo "<br><br>";

        echo "<a href='showAllocations.php'>View Resource Allocations</a>";

    }
    else {

        echo "Error: " . $conn->error;

    }

}
else {

    echo "No resource allocation was selected.";
    echo "<br><br>";

    echo "<a href='showAllocations.php'>View Resource Allocations</a>";

}


$conn->close();

?>