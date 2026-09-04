<?php
require_once "DBconnect.php";
$category_name = $_POST["category_name"];
$sql = "INSERT INTO Food_Category_Name (category_name) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category_name);
$success = $stmt->execute();
if ($success){
$category_id = $stmt->insert_id;
$sql2 = "INSERT INTO Food_Category (category_id) VALUES (?)";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $category_id);
$success = $stmt2->execute();
$stmt2->close();
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<title>Category Added</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFoodItems.php">Food Items</a>
</div>
</nav>
<div class="success-page">
<div class="success-card">
<?php if ($success) { ?>
<div class="success-icon">✓</div>
<h1>Food category added successfully!</h1>
<p class="success-message">Your new food category has been saved.</p>
<div class="success-buttons">
<a href="addFoodItem.php" class="btn">Add Food Item</a>
<a href="addFoodCategory.php" class="btn-outline">Add Another category</a> 
</div>
<?php } ?>
</div>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>