<?php
require_once "DBconnect.php";
$sql = "SELECT item_id,item_name,expiry_date,quantity,shelf_life
FROM Food_item WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Expiring Soon</title>
</head>
<body>
<h1>Food Expiring Within 7 Days</h1>
<a href="showFoodItems.php">Back to Food Items</a>
<br><br>
<table border="1" cellpadding="10">
<tr>
    <th>Item ID</th>
    <th>Item Name</th>
    <th>Expiry Date</th>
    <th>Quantity</th>
    <th>Shelf Life</th>
</tr>
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()){
?>
<tr>
    <td><?php echo $row["item_id"]; ?></td>
    <td><?php echo $row["item_name"]; ?></td>
    <td><?php echo $row["expiry_date"]; ?></td>
    <td><?php echo $row["quantity"]; ?></td>
    <td><?php echo $row["shelf_life"]; ?></td>
</tr>
<?php
}
} 
else{
    echo "<tr><td colspan='5'>No food items expiring soon.</td></tr>";
    }
?>
</table>
</body>
</html>