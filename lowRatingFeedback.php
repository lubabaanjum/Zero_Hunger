<?php
require_once "DBconnect.php";
$sql="SELECT f.feedback_id,f.rating,f.comments,f.date,
ro.org_name,d.distribution_id,d.status FROM Feedback f
JOIN Recipient_Organization ro ON f.recipient_id=ro.recipient_id
JOIN Distribution d ON f.distribution_id=d.distribution_id
WHERE f.rating<=2 ORDER BY f.rating ASC,f.date DESC";
$result=$conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Feedback Requiring Attention</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
<h1 class="page-title">Feedback Requiring Attention</h1>
<p>Low-rated feedback that may require administrative review.</p>
<?php if($result->num_rows>0){ ?>
<table>
<tr>
<th>Organization</th>
<th>Rating</th>
<th>Comments</th>
<th>Date</th>
<th>Distribution</th>
<th>Status</th>
</tr>
<?php while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?php echo htmlspecialchars($row["org_name"]); ?></td>
<td>
<span class="rating-stars">
<?php
$rating=(int)$row["rating"];
for($i=1;$i<=5;$i++){
    echo $i<=$rating ? "★" : "☆";
}
?>
</span>
<?php echo $rating; ?>/5
</td>
<td><?php echo htmlspecialchars($row["comments"]); ?></td>
<td><?php echo $row["date"]; ?></td>
<td>#<?php echo $row["distribution_id"]; ?></td>
<td><?php echo htmlspecialchars($row["status"]); ?></td>
</tr>
<?php } ?>
</table>
<?php }else{ ?>
<div class="success-card">
<h2>No Low-Rated Feedback 🎉</h2>
<p>There is currently no feedback with a rating of 2 or below.</p>
</div>
<?php } ?>
<br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>
<?php $conn->close(); ?>