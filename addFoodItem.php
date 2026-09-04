<?php
require_once "DBconnect.php";
$donation_sql="SELECT donation_id, pickup_location, donation_date FROM Food_donation ORDER BY donation_id";
$donations = $conn->query($donation_sql);
$category_sql="SELECT fcn.category_id, fcn.category_name FROM Food_Category_Name fcn JOIN Food_Category fc ON fcn.category_id = fc.category_id ORDER BY fcn.category_name";
$categories = $conn->query($category_sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Food Item</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFoodItems.php">Food Inspection</a>
</div>
</nav>
<div class="form-container">
<h1 class="page-title">Add Food Item</h1>
<form action="insertFoodItem.php" method="POST">
<div class="form-group">
<label>Item Name:</label>
<input
type="text"
name="item_name"
placeholder="Example: Bread Pack"
required
>
</div>
<div class="form-group">
<label>Expiry Date:</label>
<input
type="date"
name="expiry_date"
required
>
</div>
<div class="form-group">
<label>Quantity:</label>
<input
type="number"
name="quantity"
min="1"
required
>
</div>
<div class="form-group">
<label>Shelf Life:</label>
<input
type="text"
name="shelf_life"
placeholder="Example: 3 days"
required
>
</div>
<div class="form-group">
<label>Food Donation:</label>
<select name="donation_id" required>
<option value="">Select Food Donation --</option>
<?php
if ($donations->num_rows > 0) 
{while($row = $donations->fetch_assoc()) 
{
?>
<option value="<?php echo $row["donation_id"]; ?>">
Donation #<?php echo $row["donation_id"]; ?>
 -
<?php echo $row["pickup_location"]; ?>
                        -
<?php echo $row["donation_date"]; ?>
</option>
<?php
}}
?>
</select>
</div>
<div class="form-group">
<label>Food Category:</label>
<select name="category_id" required>
<option value="">Select Food Category</option>
<?php
if ($categories->num_rows > 0){
while($row = $categories->fetch_assoc()) {
?>
<option value="<?php echo $row["category_id"]; ?>">
<?php echo $row["category_name"]; ?>
(ID: <?php echo $row["category_id"]; ?>)
</option>
<?php
}}
?>
</select></div>
<button type="submit" class="btn">Add Food Item</button>
</form>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>