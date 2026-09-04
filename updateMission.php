<?php

require_once "DBconnect.php";

$mission_id = $_POST["mission_id"];
$mission_name = $_POST["mission_name"];
$mission_date = $_POST["mission_date"];
$status = $_POST["status"];
$partner_id = $_POST["partner_id"];


$sql = "UPDATE Rescue_Mission SET mission_name = '$mission_name',mission_date = '$mission_date',status = '$status',partner_id = '$partner_id'
WHERE mission_id = '$mission_id'";


if ($conn->query($sql)) {

    echo "Rescue mission updated successfully!";
    echo "<br><br>";

    echo "<a href='showMission.php'>View Rescue Missions</a>";

}
else {

    echo "Error: " . $conn->error;

}


$conn->close();

?>