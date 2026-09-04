<?php
require_once "DBconnect.php";
$sql="SELECT fi.item_name,fi.quantity,fi.expiry_date,MAX(ins.inspection_date) AS LatestInspection FROM Food_item fi JOIN Inspects i ON fi.item_id = i.item_id JOIN Food_Inspection ins ON i.inspection_id = ins.inspection_id
GROUP BY fi.item_id,fi.item_name,fi.quantity,fi.expiry_date HAVING fi.quantity > ALL(SELECT quantity FROM Food_item WHERE expiry_date < CURDATE())ORDER BY fi.quantity DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Complex Food Query</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFoodItems.php">Food Items</a>
<a href="showInspections.php">Inspections</a>
</div>
</nav>
<div class="container">
<h1 class="page-title">Food Quantity & Inspection Analysis</h1>
<p>Food items whose quantity is greater than the quantity of every expired food item.</p>
<br>
<table>
<tr>
<th>Food Item</th>
<th>Quantity</th>
<th>Expiry Date</th>
<th>Latest Inspection</th>
</tr>
<?php
if($result->num_rows>0){
while ($row = $result->fetch_assoc()) {
?>
<tr><td>
<?php echo $row["item_name"]; ?>
</td>
<td>
<?php echo $row["quantity"]; ?>
</td>
<td>
<?php echo $row["expiry_date"]; ?>
</td>
<td>
<?php echo $row["LatestInspection"]; ?>
</td></tr>
<?php
}} 
else{
echo "<tr>
<td colspan='4'>
No matching food items found.
</td>
</tr>";
}
?>
</table>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>