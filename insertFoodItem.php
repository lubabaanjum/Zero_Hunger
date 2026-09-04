<?php
require_once "DBconnect.php";
$item_name = $_POST["item_name"];
$expiry_date = $_POST["expiry_date"];
$quantity = $_POST["quantity"];
$shelf_life = $_POST["shelf_life"];
$donation_id = $_POST["donation_id"];
$category_id = $_POST["category_id"];
$sql = "INSERT INTO Food_item (item_name, expiry_date, quantity, shelf_life, donation_id, category_id) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisii",$item_name,$expiry_date,$quantity,$shelf_life,$donation_id,$category_id);
if ($stmt->execute()) {
    $message = "Food item added successfully!";
} 
else{
    $message = "Error adding food item.";}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Food Item Added</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="success-card">
        <h1>
            <?php echo $message; ?>
        </h1>
        <p>The food item has been saved in the database.</p>
        <br>
<a href="showFoodItems.php" class="btn">View Food Items</a>
<a href="addFoodItem.php" class="btn">Add Another Food Item</a>
<br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</div>
</body>
</html>