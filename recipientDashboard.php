<?php
session_start();

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

if($_SESSION["role"]!="Recipient"){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Recipient Dashboard</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

<h1>Recipient Dashboard</h1>

<p>
Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>
</p>

<h2>Recipient Services</h2>

<a href="showDistributionDetails.php" class="btn">
View Distributions
</a>

<a href="addFeedback.php" class="btn">
Give Feedback
</a>

<a href="logout.php" class="btn">
Logout
</a>

</div>

</body>
</html>