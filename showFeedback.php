<?php
require_once "DBconnect.php";
$sql = "SELECT f.feedback_id,f.rating,f.comments,f.date,f.distribution_id,r.org_name,d.status FROM Feedback f JOIN Recipient_Organization r ON f.recipient_id = r.recipient_id JOIN Distribution d ON f.distribution_id = d.distribution_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feedback Records</title>
</head>
<body>
<h1>Feedback and Completion Records</h1>
<a href="addFeedback.php">Give Feedback</a>
<br><br>
<table border="1" cellpadding="10">
<tr>
    <th>Feedback ID</th>
    <th>Recipient</th>
    <th>Distribution ID</th>
    <th>Rating</th>
    <th>Comments</th>
    <th>Date</th>
    <th>Distribution Status</th>
</tr>
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()){
?>
<tr>
    <td><?php echo $row["feedback_id"]; ?></td>
    <td><?php echo $row["org_name"]; ?></td>
    <td><?php echo $row["distribution_id"]; ?></td>
    <td><?php echo $row["rating"]; ?></td>
    <td><?php echo $row["comments"]; ?></td>
    <td><?php echo $row["date"]; ?></td>
    <td><?php echo $row["status"]; ?></td>
</tr>
<?php
}
} 
else{
echo "<tr><td colspan='7'>No feedback records found.</td></tr>";
}
?>
</table>
</body>
</html>