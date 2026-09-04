<?php
session_start();

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}

if($_SESSION["role"]!="Volunteer"){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Volunteer Dashboard</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

<h1>Volunteer Dashboard</h1>

<p>
Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>
</p>

<h2>Volunteer Services</h2>

<a href="showAssignments.php" class="btn">
View Assignments
</a>

<a href="showMission.php" class="btn">
View Rescue Missions
</a>

<a href="logout.php" class="btn">
Logout
</a>

</div>

</body>
</html>