<?php
require_once "DBconnect.php";
$sql="SELECT f.item_id,fi.inspection_id,fi.inspection_date,fi.quality_status,fi.remarks,f.item_name,f.expiry_date FROM Inspects i JOIN Food_item f ON i.item_id = f.item_id JOIN Food_Inspection fi ON i.inspection_id = fi.inspection_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Food Inspection Records</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFeedback.php">Feedback</a>
<a href="showFoodItems.php">Food Inspection</a>
<a href="showVolunteers.php">Volunteers</a>
</div>
</nav>
<div class="container">
<h1 class="page-title">Food Inspection Records</h1>
<a href="addInspection.php" class="btn">Add Inspection</a>
<a href="foodSummary.php" class="btn">Food Inspection Summary</a>
<br><br>
<table>
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
if($result->num_rows>0)
{while($row = $result->fetch_assoc()) 
{
?>
<tr>
<td><?php echo $row["item_id"]; ?></td>
<td><?php echo $row["item_name"]; ?></td>
<td><?php echo $row["inspection_id"]; ?></td>
<td><?php echo $row["inspection_date"]; ?></td>
<td><?php echo $row["expiry_date"]; ?></td>
<td><?php echo $row["quality_status"]; ?></td>
<td><?php echo $row["remarks"]; ?></td>
</tr
<?php
}} 
else{   
echo "<tr><td colspan='7'>No inspection records found.</td></tr>";}
?>
</table>
</div>
<a href="javascript:history.back()" class="btn">Back</a>
</body>
</html>