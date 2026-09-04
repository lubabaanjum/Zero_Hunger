<?php
require_once "DBconnect.php";

$sql="SELECT f.feedback_id,f.rating,f.comments,f.date,ro.org_name,f.distribution_id
FROM Feedback f
JOIN Recipient_Organization ro ON f.recipient_id=ro.recipient_id
WHERE LOWER(f.comments) LIKE '%late%'
OR LOWER(f.comments) LIKE '%damaged%'
OR LOWER(f.comments) LIKE '%poor%'
OR LOWER(f.comments) LIKE '%bad%'
OR LOWER(f.comments) LIKE '%delay%'
OR LOWER(f.comments) LIKE '%expired%'
ORDER BY f.date DESC";

$result=$conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Reported Feedback Issues</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">

<h1 class="page-title">Reported Feedback Issues</h1>
<p>Feedback comments containing common problem keywords that may require attention.</p>

<?php if($result && $result->num_rows>0){ ?>
<table>
<tr>
<th>Organization</th>
<th>Rating</th>
<th>Comment</th>
<th>Date</th>
<th>Distribution</th>
<th>Issue Detected</th>
</tr>

<?php while($row=$result->fetch_assoc()){
$comment=strtolower($row["comments"]);
$issue="Reported Issue";

if(strpos($comment,"late")!==false || strpos($comment,"delay")!==false){
    $issue="Delivery Delay";
}elseif(strpos($comment,"damaged")!==false){
    $issue="Damaged Food";
}elseif(strpos($comment,"expired")!==false){
    $issue="Expired Food";
}elseif(strpos($comment,"poor")!==false || strpos($comment,"bad")!==false){
    $issue="Poor Experience";
}
?>
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
<td><strong><?php echo $issue; ?></strong></td>
</tr>
<?php } ?>
</table>

<?php }else{ ?>
<div class="success-card">
<h2>No Reported Issues</h2>
<p>No feedback currently contains the monitored issue keywords.</p>
</div>
<?php } ?>

<br><br>
<a href="showFeedback.php" class="btn">Back to Feedback</a>
<a href="javascript:history.back()" class="btn">Back</a>

</div>
</body>
</html>
<?php $conn->close(); ?>