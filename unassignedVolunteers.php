<?php
require_once "DBconnect.php";
$sql = "SELECT  v.volunteer_id, v.name, v.phone, v.area, v.availability FROM Volunteer v LEFT JOIN Volunteer_Assignment va   ON v.volunteer_id = va.volunteer_id WHERE va.assignment_id IS NULL ORDER BY v.name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Unassigned Volunteers</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showVolunteers.php">Volunteers</a>
<a href="showAssignments.php">Assignments</a>
</div>
</nav>
<div class="container">
<h1 class="page-title">Unassigned Volunteers</h1>
<p>Volunteers who have not received any assignment yet.</p>
<br>
<table>
<tr>
<th>Volunteer ID</th>
<th>Name</th>
<th>Phone</th>
<th>Area</th>
<th>Availability</th>
</tr>
<?php
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
?>
<tr>
<td><?php echo $row["volunteer_id"]; ?></td>
<td><?php echo $row["name"]; ?></td>
<td><?php echo $row["phone"]; ?></td>
<td><?php echo $row["area"]; ?></td>
<td><?php echo $row["availability"]; ?></td>
</tr>
<?php
}
}
else{
echo "<tr>
<td colspan='5'>
All registered volunteers currently have assignments.
</td>
</tr>";
}
?>
</table>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>