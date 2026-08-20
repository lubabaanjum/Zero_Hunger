<?php
require_once "DBconnect.php";
$volunteer_id = $_POST["volunteer_id"];
$assignment_time = $_POST["assignment_time"];
$location = $_POST["location"];
$sql = "INSERT INTO Volunteer_Assignment
        (assignment_time, location, volunteer_id)
        VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssi",
    $assignment_time,
    $location,
    $volunteer_id
);
if ($stmt->execute()) {
    echo "Volunteer assignment added successfully!";
    echo "<br><br>";
    echo "<a href='showAssignments.php'>View Assignments</a>";
} 
else{
echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>