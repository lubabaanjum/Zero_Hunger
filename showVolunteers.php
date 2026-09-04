<?php
require_once "DBconnect.php";
$totalVolunteers=$conn->query("SELECT COUNT(*) AS total FROM Volunteer")->fetch_assoc()["total"];
$assigned=$conn->query("SELECT COUNT(DISTINCT volunteer_id) AS total FROM Volunteer_Assignment")->fetch_assoc()["total"];
$unassigned=$conn->query("SELECT COUNT(*) AS total FROM Volunteer v WHERE NOT EXISTS(SELECT 1 FROM Volunteer_Assignment va WHERE va.volunteer_id=v.volunteer_id)")->fetch_assoc()["total"];
$areas=$conn->query("SELECT COUNT(DISTINCT area) AS total FROM Volunteer")->fetch_assoc()["total"];
$sql="SELECT * FROM Volunteer";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Volunteers</title>
<link rel="stylesheet" href="css/style.css">
</head>
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
<section class="dashboard-stats">
<h2>Volunteer Overview</h2>
<div class="stats-container">

<a href="showVolunteers.php" class="stat-card">
<div class="stat-icon">👥</div>
<h3><?php echo $totalVolunteers; ?></h3>
<p>Total Volunteers</p>
</a>

<a href="showAssignments.php" class="stat-card">
<div class="stat-icon">✓</div>
<h3><?php echo $assigned; ?></h3>
<p>Assigned Volunteers</p>
</a>

<a href="unassignedVolunteers.php" class="stat-card">
<div class="stat-icon">⌛</div>
<h3><?php echo $unassigned; ?></h3>
<p>Unassigned Volunteers</p>
</a>

<a href="areaCoverage.php" class="stat-card">
<div class="stat-icon">📍</div>
<h3><?php echo $areas; ?></h3>
<p>Areas Covered</p>
</a>

</div>
</section>
<div class="container">
<h1 class="page-title">Volunteers</h1>
<a href="addVolunteer.php" class="btn">Add Volunteer</a>
<a href="assignVolunteer.php" class="btn">Assign Volunteer</a>
<a href="showAssignments.php" class="btn">View Assignments</a>
<a href="findSuitableVolunteer.php" class="btn">Find Suitable Volunteer</a>
<a href="volunteerWorkload.php" class="btn">View Workload</a>
<a href="unassignedVolunteers.php" class="btn">Unassigned Volunteers</a>
<a href="areaCoverage.php" class="btn">Area Coverage</a>
<br><br>
<table><tr>
<th>Volunteer ID</th>
<th>Name</th>
<th>Phone</th>
<th>Area</th>
<th>Availability</th>
<th>Action</th>
</tr>
<?php
if($result->num_rows>0){
while($row=$result->fetch_assoc())
{
?>
<tr>
<td><?php echo $row["volunteer_id"]; ?></td>
<td><?php echo $row["name"]; ?></td>
<td><?php echo $row["phone"]; ?></td>
<td><?php echo $row["area"]; ?></td>
<td><?php echo $row["availability"]; ?></td>
<td>
<a href="deleteVolunteer.php?id=<?php echo $row["volunteer_id"]; ?>"
class="btn"
onclick="return confirm('Are you sure you want to delete this volunteer? This may also delete related assignment records.');">
Delete
</a>
</td>
</tr>
<?php
}} 
else{
echo "<tr><td colspan='4'>No volunteers found.</td></tr>";}
?>
</table>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>