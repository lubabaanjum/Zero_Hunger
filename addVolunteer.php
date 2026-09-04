<!DOCTYPE html>
<html>
<head>
<title>Add Volunteer</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showVolunteers.php">Volunteers</a>
</div>
</nav>
<div class="form-container">
<h1 class="page-title">Add Volunteer</h1>
<form action="insertVolunteer.php" method="POST">
<div class="form-group">
<label>Name:</label>
<input type="text" name="name" required>
</div>
<div class="form-group">
<label>Phone:</label>
<input type="text" name="phone" required>
</div>
<div class="form-group">
<label>Area:</label>
<input type="text" name="area" required>
</div>
<div class="form-group">
<label>Availability:</label>
<input type="text" name="availability" required>
</div>
<button type="submit" class="btn">Add Volunteer</button>
</form>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>