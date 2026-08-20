<?php
require_once "DBconnect.php";
$sql = "SELECT d.distribution_id,d.mission_id,d.pickup_time,d.delivery_time,d.status,a.allocation_id,a.recipient_id
FROM Distribution d JOIN Resource_Allocation a ON d.allocation_id = a.allocation_id
WHERE d.status = 'Completed'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Completed Distributions</title>
</head>
<body>
<h1>Completed Distribution Records</h1>
<a href="showFeedback.php">View Feedback</a>
<br><br>
<table border="1" cellpadding="10">
<tr>
    <th>Distribution ID</th>
    <th>Mission ID</th>
    <th>Allocation ID</th>
    <th>Recipient ID</th>
    <th>Pickup Time</th>
    <th>Delivery Time</th>
    <th>Status</th>
</tr>
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?php echo $row["distribution_id"]; ?></td>
    <td><?php echo $row["mission_id"]; ?></td>
    <td><?php echo $row["allocation_id"]; ?></td>
    <td><?php echo $row["recipient_id"]; ?></td>
    <td><?php echo $row["pickup_time"]; ?></td>
    <td><?php echo $row["delivery_time"]; ?></td>
    <td><?php echo $row["status"]; ?></td>
</tr>
<?php
    }} 
else{
    echo "<tr><td colspan='7'>No completed distributions found.</td></tr>";
}
?>
</table>
</body>
</html>