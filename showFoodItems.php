<?php
require_once "DBconnect.php";
$totalFood=$conn->query("SELECT COUNT(*) AS total FROM Food_item")->fetch_assoc()["total"];
$expired=$conn->query("SELECT COUNT(*) AS total FROM Food_item WHERE expiry_date<CURDATE()")->fetch_assoc()["total"];
$expiring=$conn->query("SELECT COUNT(*) AS total FROM Food_item WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(),INTERVAL 7 DAY)")->fetch_assoc()["total"];
$safe=$conn->query("SELECT COUNT(*) AS total FROM Food_item WHERE expiry_date>DATE_ADD(CURDATE(),INTERVAL 7 DAY)")->fetch_assoc()["total"];
$sql="SELECT fi.item_id,fi.item_name,fi.quantity,fi.expiry_date,fi.shelf_life,fc.category_name, DATEDIFF(fi.expiry_date,CURDATE()) AS days_left FROM Food_item fi JOIN Food_Category_Name fc ON fi.category_id=fc.category_id ORDER BY fi.expiry_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Food Items</title>
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
<section class="dashboard-stats">
<h2>Food Overview</h2>
<div class="stats-container">

<a href="showFoodItems.php" class="stat-card">
<div class="stat-icon">🍱</div>
<h3><?php echo $totalFood; ?></h3>
<p>Total Food Items</p>
</a>

<a href="expiredFood.php" class="stat-card">
<div class="stat-icon">⚠️</div>
<h3><?php echo $expired; ?></h3>
<p>Expired</p>
</a>

<a href="expiringSoon.php" class="stat-card">
<div class="stat-icon">⏰</div>
<h3><?php echo $expiring; ?></h3>
<p>Expiring Soon</p>
</a>

<a href="foodSummary.php" class="stat-card">
<div class="stat-icon">✓</div>
<h3><?php echo $safe; ?></h3>
<p>Safe</p>
</a>


</div>
</section>
<div class="container">
<h1 class="page-title">Food Items</h1>
<div class="action-buttons">
<a href="addFoodItem.php" class="btn">Add Food Item</a>
<a href="expiredFood.php" class="btn">View Expired Food</a>
<a href="expiringSoon.php" class="btn">View Food Expiring Soon</a>
<a href="showInspections.php" class="btn">View Inspection Records</a>
<a href="addFoodDonation.php" class="btn">Add Food Donation</a>
<a href="addFoodCategory.php" class="btn">Add Food Category</a>
<a href="complexFoodQuery.php" class="btn">Food Analysis</a>
<a href="foodSummary.php" class="btn">Expiry Summary</a>


</div>
<table>
<tr>
 <th>Item ID</th>
 <th>Item Name</th>
 <th>Expiry Date</th>
 <th>Quantity</th>
<th>Shelf Life</th>
<th>Status</th>
<th>Action</th>
</tr>
<?php
if($result->num_rows>0) 
{while ($row = $result->fetch_assoc()) 
{
?>
<tr> <td><?php echo $row["item_id"]; ?></td>
<td><?php echo $row["item_name"]; ?></td>
<td><?php echo $row["expiry_date"]; ?></td>
<td><?php echo $row["quantity"]; ?></td>
<td><?php echo $row["shelf_life"]; ?></td>
<td>
<?php
$days=$row["days_left"];

if($days<0){
    echo '<span class="status-expired">Expired</span>';
}
elseif($days<=3){
    echo '<span class="status-urgent">Urgent - '.$days.' Expires today</span>';
}
elseif($days<=7){
    echo '<span class="status-soon">Expiring Soon - '.$days.' days left</span>';
}
else{
    echo '<span class="status-safe">Safe - '.$days.' days left</span>';
}
?>
</td>
<td>
    <a href="deleteFoodItem.php?id=<?php echo $row['item_id']; ?>"
       class="btn"
       onclick="return confirm('Are you sure you want to delete this food item?');">
       Delete
    </a>
</td>
</tr>
<?php
}} 
else{
echo "<tr><td colspan='5'>No food items found.</td></tr>";}
?>
</table>
<br><br>

<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>