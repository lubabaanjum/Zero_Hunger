<?php
session_start();

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

if($_SESSION["role"]!="Donor"){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Donor Dashboard</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

<h1>Donor Dashboard</h1>

<p>
Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>
</p>

<h2>Donor Services</h2>

<a href="addFoodDonation.php" class="btn">
Donate Food
</a>

<a href="showFoodItems.php" class="btn">
View Food Items
</a>

<a href="logout.php" class="btn">
Logout
</a>

</div>

</body>
</html>