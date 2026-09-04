<!DOCTYPE html>
<html>
<head><title>Assign Volunteer</title>
<link rel="stylesheet" href="css/style.css"></head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFeedback.php">Feedback</a>
<a href="showFoodItems.php">Food Inspection</a>
<a href="showVolunteers.php">Volunteers</a></div>
</nav>
<div class="form-container">
<h1 class="page-title">Add Volunteer Assignment</h1>
<form action="insertAssignment.php" method="POST"><div class="form-group">
<label>Volunteer ID:</label>
<input type="number" name="volunteer_id" required></div>
<div class="form-group">
<label>Assignment Time:</label>
<input type="datetime-local" name="assignment_time" required></div>
<div class="form-group">
<label>Location:</label>
<input type="text" name="location" required></div>
<button type="submit" class="btn">Assign Volunteer</button>
</form>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>