<?php
require_once "DBconnect.php";
$sql="SELECT va.assignment_id, va.assignment_time, va.location,va.volunteer_id, v.name AS volunteer_name, v.phone,v.area, v.availability FROM Volunteer_Assignment va JOIN Volunteer v ON va.volunteer_id = v.volunteer_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head><title>Volunteer Assignments</title>
<link rel="stylesheet" href="css/style.css"></head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFeedback.php">Feedback</a>
<a href="showFoodItems.php">Food Inspection</a>
<a href="showVolunteers.php">Volunteers</a>
</div>
</nav>
<div class="container"><h1 class="page-title">Volunteer Assignments</h1>
<a href="assignVolunteer.php" class="btn">Add Volunteer Assignment</a>
<br><br>
<table>
<tr>
<th>Assignment ID</th>
<th>Volunteer ID</th>
<th>Volunteer Name</th>
<th>Phone</th>
<th>Area</th>
<th>Availability</th>
<th>Assignment Time</th>
<th>Location</th>
<th>Action</th>
</tr>
<?php
if($result->num_rows>0){
while ($row = $result->fetch_assoc()){
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
<td>
<a href="deleteAssignment.php?id=<?php echo $row["assignment_id"]; ?>"
class="btn"
onclick="return confirm('Are you sure you want to delete this assignment?');">
Delete
</a>
</td>
</tr>
<?php
}} 
else{        
echo "<tr><td colspan='8'>No volunteer assignments found.</td></tr>";}
?>
</table>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>