<?php
require_once "DBconnect.php";
$sql = "SELECT
            va.assignment_id,
            va.assignment_time,
            va.location,
            va.volunteer_id,
            v.name AS volunteer_name,
            v.phone,
            v.area,
            v.availability
        FROM Volunteer_Assignment va
        JOIN Volunteer v
            ON va.volunteer_id = v.volunteer_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Assignments</title>
</head>
<body>
<h1>Volunteer Assignments</h1>
<a href="assignVolunteer.php">
    Add Volunteer Assignment
</a>
<br><br>
<table border="1" cellpadding="10">
<tr>
    <th>Assignment ID</th>
    <th>Volunteer ID</th>
    <th>Volunteer Name</th>
    <th>Phone</th>
    <th>Area</th>
    <th>Availability</th>
    <th>Assignment Time</th>
    <th>Location</th>
</tr>
<?php
if ($result->num_rows > 0){
    while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?php echo $row["assignment_id"]; ?></td>
    <td><?php echo $row["volunteer_id"]; ?></td>
    <td><?php echo $row["volunteer_name"]; ?></td>
    <td><?php echo $row["phone"]; ?></td>
    <td><?php echo $row["area"]; ?></td>
    <td><?php echo $row["availability"]; ?></td>
    <td><?php echo $row["assignment_time"]; ?></td>
    <td><?php echo $row["location"]; ?></td>
</tr>
<?php
    }
} 
else{
    echo "<tr><td colspan='8'>
            No volunteer assignments found.
          </td></tr>";
}
?>
</table>
</body>
</html>