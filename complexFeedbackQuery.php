<?php
require_once "DBconnect.php";
$sql = "SELECT ro.org_name,AVG(f.rating) AS AvgRating FROM Recipient_Organization ro JOIN Feedback f ON ro.recipient_id = f.recipient_id GROUP BY ro.recipient_id,ro.org_name  HAVING AVG(f.rating) > ANY
        (SELECT AVG(f2.rating) FROM Feedback f2 JOIN Distribution d ON f2.distribution_id = d.distribution_id WHERE d.status = 'Completed' GROUP BY f2.recipient_id) ORDER BY AvgRating DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head><title>Feedback Query</title>
<link rel="stylesheet" href="css/style.css"></head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFeedback.php">Feedback</a>
<a href="showCompleted.php">Completed Records</a>
</div>
</nav>
<div class="container">
<h1 class="page-title">Recipient Feedback Analysis</h1>
<p>Recipient organizations whose average feedback rating is greater than the average rating of at least one recipient with completed distributions.</p>
<br>
<table>
<tr>
<th>Recipient Organization</th>
<th>Average Rating</th>
</tr>
<?php
if($result->num_rows > 0){
while($row =$result->fetch_assoc()){
?>
<tr>
<td>
<?php echo $row["org_name"]; ?>
</td>
<td>
<?php echo number_format($row["AvgRating"], 2); ?>
</td>
</tr>
<?php
}}
else{
echo "<tr>
<td colspan='2'>
No matching records found.
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