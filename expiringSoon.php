<?php
require_once "DBconnect.php";
$sql="SELECT item_id, item_name, quantity, expiry_date, shelf_life, DATEDIFF(expiry_date, CURDATE()) AS days_left FROM Food_item WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) ORDER BY expiry_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Expiring Soon</title>
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
<h1 class="page-title">Food Expiring Within 7 Days</h1>
<br>
<table><tr>
<th>Item ID</th>
<th>Item Name</th>
<th>Expiry Date</th>
<th>Quantity</th>
<th>Shelf Life</th></tr>
<?php
if($result->num_rows>0)
{while ($row = $result->fetch_assoc()){
?>
<tr>
<td><?php echo $row["item_id"]; ?></td>
<td><?php echo $row["item_name"]; ?></td>
<td><?php echo $row["expiry_date"]; ?></td>
<td><?php echo $row["quantity"]; ?></td>
<td><?php echo $row["shelf_life"]; ?></td>
</tr>
<?php
}}
else{
echo "<tr><td colspan='5'>No food items expiring soon.</td></tr>";}
?>
</table>
<br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>