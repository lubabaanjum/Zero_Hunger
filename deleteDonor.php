<?php
require_once "DBconnect.php";
$id= $_GET["id"];
$sql= "DELETE FROM Donor WHERE donor_id=?";
$stmt= $conn->prepare($sql);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo "Donor deleted";
    echo "<br><br>";
    echo "<a href='showdonors.php'>View Donors</a>";
} 
else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>