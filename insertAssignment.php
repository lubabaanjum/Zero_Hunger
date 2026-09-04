<?php
require_once "DBconnect.php";
$volunteer_id = $_POST["volunteer_id"];
$assignment_time = $_POST["assignment_time"];
$location = $_POST["location"];
$sql = "INSERT INTO Volunteer_Assignment (assignment_time, location, volunteer_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi",$assignment_time,$location,$volunteer_id);
$success = $stmt->execute();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">
<title>Volunteer Assignment</title>
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
<div class="success-page">
<div class="success-card">
<?php if ($success){ ?>
<h1>Volunteer assignment added successfully!</h1>
<p class="success-message">The volunteer assignment has been recorded successfully.</p>
<div class="success-buttons">
<a href="showAssignments.php" class="btn">View Assignments</a>
<a href="assignVolunteer.php" class="btn-outline"> Add Another Assignment</a></div>
<?php } else { ?>
<h1>Assignment could not be added</h1>
<p class="success-message">Please check the volunteer and try again.</p>
<a href="assignVolunteer.php" class="btn">Try Again</a>
<?php } ?>
</div>
</div>
</body>
</html>