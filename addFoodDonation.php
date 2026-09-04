<!DOCTYPE html>
<html>
<head>
<title>Add Food Donation</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showDonors.php">Donors</a>
<a href="showFoodItems.php">Food Items</a>
</div>
</nav>
<div class="form-container">
<h1>Add Food Donation</h1>
<form action="insertFoodDonation.php" method="POST">
<div class="form-group">
<label>Pickup Location:</label>
<input type="text" name="pickup_location" required>
</div>
<div class="form-group">
<label>Donation Date:</label>
<input type="date" name="donation_date" required>
</div>
<div class="form-group">
<label>Status:</label>
<input type="text" name="status" required>
</div>
<div class="form-group">
<label>Donor ID:</label>
<input type="number" name="donor_id" required>
</div>
<button type="submit" class="btn">Add Donation</button>
</form>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>