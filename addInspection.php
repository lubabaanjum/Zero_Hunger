<!DOCTYPE html>
<html>
<head><title>Add Food Inspection</title>
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
<h1 class="page-title">Add Food Inspection</h1>
<form action="insertInspection.php" method="POST">
<div class="form-group">
<label>Item ID:</label>
<input type="number" name="item_id" required></div>
<div class="form-group">
<label>Inspection Date:</label>
<input type="date" name="inspection_date" required></div>
<div class="form-group">
<label>Quality Status:</label>
<input type="text" name="quality_status" required></div>
<div class="form-group">
<label>Remarks:</label>
<textarea name="remarks"></textarea></div>
<button type="submit" class="btn">Add Inspection</button>
</form>
</div>
<a href="javascript:history.back()" class="btn">Back</a>
</body>
</html>