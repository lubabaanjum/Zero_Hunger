<?php
require_once "DBconnect.php";
$areas=$conn->query("SELECT DISTINCT area FROM Volunteer ORDER BY area");
$availabilityList=$conn->query("SELECT DISTINCT availability FROM Volunteer ORDER BY availability");
$result=null;
if(isset($_GET["area"]) && isset($_GET["availability"])){
    $area=$_GET["area"];
    $availability=$_GET["availability"];

    $sql="SELECT v.volunteer_id,v.name,v.phone,v.area,v.availability, COUNT(va.assignment_id) AS Workload FROM Volunteer v LEFT JOIN Volunteer_Assignment va ON v.volunteer_id=va.volunteer_id WHERE v.area=? AND v.availability=?
    GROUP BY v.volunteer_id,v.name,v.phone,v.area,v.availability
    ORDER BY Workload ASC,v.name ASC";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ss",$area,$availability);
    $stmt->execute();
    $result=$stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Find Suitable Volunteer</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
<h1 class="page-title">Find Suitable Volunteer</h1>
<form method="GET" class="search-box">
<div class="search-row">
<div class="search-field">
<label>Area</label>
<select name="area" required>
<option value="">Select Area</option>
<?php while($row=$areas->fetch_assoc()){ ?>
<option value="<?php echo htmlspecialchars($row["area"]); ?>"
<?php if(isset($_GET["area"]) && $_GET["area"]==$row["area"]) echo "selected"; ?>>
<?php echo htmlspecialchars($row["area"]); ?>
</option>
<?php } ?>
</select>
</div>
<div class="search-field">
<label>Availability</label>
<select name="availability" required>
<option value="">Select Availability</option>
<?php while($row=$availabilityList->fetch_assoc()){ ?>
<option value="<?php echo htmlspecialchars($row["availability"]); ?>"
<?php if(isset($_GET["availability"]) && $_GET["availability"]==$row["availability"]) echo "selected"; ?>>
<?php echo htmlspecialchars($row["availability"]); ?>
</option>
<?php } ?>
</select>
</div>
</div>
<button type="submit" class="btn">Find Volunteer</button>
</form>
<?php if($result!==null){ ?>
<br>
<h2>Suitable Volunteers</h2>
<?php if($result->num_rows>0){ ?>
<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Area</th>
<th>Availability</th>
<th>Previous Assignments</th>
<th>Recommendation</th>
</tr>
<?php
$first=true;
while($row=$result->fetch_assoc()){
?>
<tr <?php if($first) echo 'style="background-color:#d4edda;font-weight:bold;"'; ?>>
<td><?php echo $row["volunteer_id"]; ?></td>
<td><?php echo htmlspecialchars($row["name"]); ?></td>
<td><?php echo htmlspecialchars($row["phone"]); ?></td>
<td><?php echo htmlspecialchars($row["area"]); ?></td>
<td><?php echo htmlspecialchars($row["availability"]); ?></td>
<td><?php echo $row["Workload"]; ?></td>
<td>
<?php
if($first) echo "⭐ Recommended";
else echo "-";
?>
</td>
</tr>
<?php
$first=false;
}
?>
</table>
<?php }else{ ?>
<p>No suitable volunteers found.</p>
<?php } ?>
<?php } ?>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>