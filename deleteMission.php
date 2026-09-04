<?php

require_once "DBconnect.php";


if (isset($_GET["mission_id"])) {

    $mission_id = $_GET["mission_id"];


    $sql = "DELETE FROM Rescue_Mission WHERE mission_id = '$mission_id'";


    if ($conn->query($sql)) {

        echo "Rescue mission deleted successfully!";
        echo "<br><br>";

        echo "<a href='showMission.php'>View Rescue Missions</a>";

    }
    else {

        echo "Error: " . $conn->error;

    }

}
else {

    echo "No rescue mission was selected.";
    echo "<br><br>";

    echo "<a href='showMission.php'>View Rescue Missions</a>";

}


$conn->close();

?>