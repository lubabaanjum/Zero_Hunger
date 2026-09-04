<?php

require_once "DBconnect.php";

$mission_name = $_POST["mission_name"];
$mission_date = $_POST["mission_date"];
$status = $_POST["status"];
$partner_id = $_POST["partner_id"];

$sql = "INSERT INTO Rescue_Mission
        (mission_name, mission_date, status, partner_id)
        VALUES ('$mission_name', '$mission_date', '$status', '$partner_id')";

if ($conn->query($sql)) {

    echo "Rescue mission added successfully!";
    echo "<br><br>";
    echo "<a href='showMission.php'>View Rescue Missions</a>";

}
else {

    echo "Error: " . $conn->error;

}

$conn->close();

?>