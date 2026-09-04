<?php
require_once "DBconnect.php";
$name = $_POST["name"];
$phone = $_POST["phone"];
$area = $_POST["area"];
$availability = $_POST["availability"];
$sql = "INSERT INTO Volunteer (name, phone, area, availability) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss",$name,$phone,$area,$availability);
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
<title>Volunteer Added</title>
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
<?php if($success){ ?>
<h1>Volunteer added successfully!</h1>
<p class="success-message">The volunteer has been added to the Zero Hunger Network.<br></p>
<div class="success-buttons">
<a href="showVolunteers.php"
class="btn">View Volunteer</a>
<a href="addVolunteer.php"
class="btn-outline">Add Another Volunteer</a></div>
<?php } else { ?>
<div class="error-icon">!</div>
<h1>Unable to add volunteer</h1>
<p class="success-message">Please try again.</p>
<a href="addVolunteer.php"
class="btn">Try Agai</a>
<?php } ?>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</div>
</body>
</html>