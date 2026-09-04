<?php
require_once "DBconnect.php";
$pickup_location = $_POST["pickup_location"];
$donation_date = $_POST["donation_date"];
$status = $_POST["status"];
$donor_id = $_POST["donor_id"];
$sql = "INSERT INTO Food_donation (pickup_location, donation_date, status, donor_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi",$pickup_location,$donation_date,$status,$donor_id);
$success = $stmt->execute();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<title>Food Donation Added</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFoodItems.php">Food Items</a>
<a href="showVolunteers.php">Volunteers</a>
</div>
</nav>
<div class="success-page">
<div class="success-card">
<?php if ($success) { ?>
<div class="success-icon">✓</div>
<h1>Food donation added successfully!</h1>
<p class="success-message">The donation has been recorded in the system.</p>
<div class="success-buttons">
<a href="addFoodItem.php" class="btn">Continue to Food Items</a>
<a href="addFoodDonation.php" class="btn-outline">Add Another Donation</a>
</div>
<div class="success-note">Together we can build a hunger-free community.</div>
<?php } ?>
</div>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>