<?php

require_once "DBconnect.php";

$allocation_id = $_POST["allocation_id"];
$mission_id = $_POST["mission_id"];


if($mission_id == "")
{
    $sql = "INSERT INTO Distribution
            (mission_id, pickup_time, status, delivery_time, allocation_id)
            VALUES
            (NULL, NULL, 'Pending', NULL, '$allocation_id')";
}
else
{
    $sql = "INSERT INTO Distribution
            (mission_id, pickup_time, status, delivery_time, allocation_id)
            VALUES
            ('$mission_id', NULL, 'Pending', NULL, '$allocation_id')";
}


if($conn->query($sql))
{
    header("Location: showDistributions.php");
    exit();
}
else
{
    echo "Error: " . $conn->error;
}


$conn->close();

?>