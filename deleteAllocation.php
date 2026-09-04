<?php

require_once "DBconnect.php";

if (isset($_GET["allocation_id"])) {

    $allocation_id = $_GET["allocation_id"];

    $sql = "DELETE FROM Resource_Allocation WHERE allocation_id = '$allocation_id'";

    if ($conn->query($sql)) {

        echo "Resource allocation deleted successfully!";
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