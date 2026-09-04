<?php
require_once "DBconnect.php";
$sql = "SELECT v.volunteer_id,v.name,v.area,v.availability, COUNT(va.assignment_id) AS TotalAssignments FROM Volunteer v LEFT JOIN Volunteer_Assignment va ON v.volunteer_id = va.volunteer_id
GROUP BY v.volunteer_id,v.name,v.area,v.availability HAVING COUNT(va.assignment_id) >= 1 ORDER BY TotalAssignments DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Volunteer Workload Analysis</title>
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
<h1 class="page-title">Volunteer Workload Analysis</h1>
<p>Volunteers who have received assignments and compares their workload.</p><br>
<table>
<tr>
<th>Volunteer ID</th>
<th>Name</th>
<th>Area</th>
<th>Availability</th>
<th>Total Assignments</th>
</tr>
<?php
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
?>
<tr>
<td><?php echo $row["volunteer_id"]; ?></td>
<td><?php echo $row["name"]; ?></td>
<td><?php echo $row["area"]; ?></td>
<td><?php echo $row["availability"]; ?></td>
<td><?php echo $row["TotalAssignments"]; ?></td>
</tr>
<?php
}
}
else {
echo "<tr><td colspan='5'>No assignment records found.</td></tr>";
}
?>
</table>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>