<!DOCTYPE html>
<html>
<head><title>Add Feedback</title>
<link rel="stylesheet" href="css/style.css">
</head>
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
<h1 class="page-title">Give Feedback</h1>
<form action="insertFeedback.php" method="POST">
<div class="form-group"><label>Rating:</label>
<input type="number" name="rating" min="1" max="5" required></div>
<div class="form-group"><label>Comments:</label>
<textarea name="comments"></textarea></div>
<div class="form-group">
<label>Date:</label>
<input type="date" name="date" required></div>
<div class="form-group">
<label>Distribution ID:</label>
<input type="number" name="distribution_id" required></div>
<div class="form-group">
<label>Recipient ID:</label>
<input type="number" name="recipient_id" required></div>
<button type="submit" class="btn">Submit Feedback</button>
</form>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>