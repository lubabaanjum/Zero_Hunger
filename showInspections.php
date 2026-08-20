<?php
require_once "DBconnect.php";
$sql = "SELECT f.item_id,fi.inspection_id,fi.inspection_date,fi.quality_status,fi.remarks,f.item_name,f.expiry_date FROM Inspects i JOIN Food_item f
ON i.item_id = f.item_id JOIN Food_Inspection fi ON i.inspection_id = fi.inspection_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Food Inspection Records</title>
</head>
<body>
<h1>Food Inspection Records</h1>
<a href="addInspection.php">Add Inspection</a>
<br><br>
<table border="1" cellpadding="10">
<tr>
    <th>Item ID</th>
    <th>Item Name</th>
    <th>Inspection ID</th>
    <th>Inspection Date</th>
    <th>Expiry Date</th>
    <th>Quality Status</th>
    <th>Remarks</th>
</tr>
<?php
if ($result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
?>
<tr>
    <td><?php echo $row["item_id"]; ?></td>
    <td><?php echo $row["item_name"]; ?></td>
    <td><?php echo $row["inspection_id"]; ?></td>
    <td><?php echo $row["inspection_date"]; ?></td>
    <td><?php echo $row["expiry_date"]; ?></td>
    <td><?php echo $row["Quality_status"]; ?></td>
    <td><?php echo $row["remarks"]; ?></td>
</tr>
<?php
}} 
else{
    echo "<tr><td colspan='7'>No inspection records found.</td></tr>";}
?>
</table>
</body>
</html>