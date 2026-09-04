<?php
require_once "DBconnect.php";
$sql="SELECT item_id, item_name, expiry_date, quantity, shelf_life FROM Food_item WHERE expiry_date < CURDATE()";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head><title>Expired Food</title>
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
<div class="container">
<h1 class="page-title">Expired Food Items</h1>
<br>
<table><tr>
<th>Item ID</th><th>Item Name</th><th>Expiry Date</th><th>Quantity</th><th>Shelf Life</th></tr>
<?php
if($result->num_rows>0){
while ($row = $result->fetch_assoc()) {
?>
<tr>
<td><?php echo $row["item_id"]; ?></td>
<td><?php echo $row["item_name"]; ?></td>
<td><?php echo $row["expiry_date"]; ?></td>
<td><?php echo $row["quantity"]; ?></td>
<td><?php echo $row["shelf_life"]; ?></td></tr>
<?php
}} 
else{
echo "<tr><td colspan='5'>No expired food found.</td></tr>";}
?>
</table>
<br>
<a href="javascript:history.back()" class="btn">Back</a>
</body>
</html>