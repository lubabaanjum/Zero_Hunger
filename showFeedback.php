<?php
require_once "DBconnect.php";
$totalFeedback=$conn->query("SELECT COUNT(*) AS total FROM Feedback")->fetch_assoc()["total"];
$avgRating=$conn->query("SELECT ROUND(AVG(rating),1) AS avg FROM Feedback")->fetch_assoc()["avg"];
if($avgRating===NULL) $avgRating=0;
$lowRating=$conn->query("SELECT COUNT(*) AS total FROM Feedback WHERE rating<=2")->fetch_assoc()["total"];
$fiveStar=$conn->query("SELECT COUNT(*) AS total FROM Feedback WHERE rating=5")->fetch_assoc()["total"];
$completed=$conn->query("SELECT COUNT(*) AS total FROM Distribution WHERE status='Completed'")->fetch_assoc()["total"];
$sql="SELECT f.feedback_id,f.rating,f.comments,f.date,f.distribution_id,r.org_name,d.status FROM Feedback f JOIN Recipient_Organization r ON f.recipient_id = r.recipient_id JOIN Distribution d ON f.distribution_id = d.distribution_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Feedback Records</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo"> ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFeedback.php">Feedback</a>
<a href="showFoodItems.php">Food Inspection</a>
<a href="showVolunteers.php">Volunteers</a>
</div>
</nav>  
<section class="dashboard-stats">
<h2>Feedback Overview</h2>
<div class="stats-container">

<a href="showFeedback.php" class="stat-card">
<div class="stat-icon">💬</div>
<h3><?php echo $totalFeedback; ?></h3>
<p>Total Feedback</p>
</a>

<a href="feedbackSummary.php" class="stat-card">
<div class="stat-icon">⭐</div>
<h3><?php echo $avgRating; ?>/5</h3>
<p>Average Rating</p>
</a>

<a href="feedbackSummary.php" class="stat-card">
<div class="stat-icon">🌟</div>
<h3><?php echo $fiveStar; ?></h3>
<p>5-Star Feedback</p>
</a>
<a href="lowRatingFeedback.php" class="stat-card">
<div class="stat-icon">⚠️</div>
<h3><?php echo $lowRating; ?></h3>
<p>Needs Attention</p>
</a>
<a href="showCompleted.php" class="stat-card">
<div class="stat-icon">✓</div>
<h3><?php echo $completed; ?></h3>
<p>Completed Distributions</p>
</a>

</div>
</section>  
<div class="container">
<h1 class="page-title">Feedback and Completion Records</h1>
<a href="addFeedback.php" class="btn">Provide Feedback</a>
<a href="feedbackSummary.php" class="btn">Feedback Summary</a>
<a href="complexFeedbackQuery.php" class="btn">Feedback Analysis</a>
<a href="lowRatingFeedback.php" class="btn">Feedback Requiring Attention</a>
<a href="feedbackIssues.php" class="btn">View Reported Issues</a>
<br><br>
<table>
<tr>
<th>Feedback ID</th>
<th>Recipient</th>
<th>Distribution ID</th>
<th>Rating</th>
<th>Comments</th>
<th>Date</th>
<th>Distribution Status</th>
<th>Action</th>
</tr>
<?php
if($result->num_rows>0) 
{while ($row = $result->fetch_assoc()){
?>
<tr>
<td><?php echo $row["feedback_id"]; ?></td>
<td><?php echo $row["org_name"]; ?></td>
<td><?php echo $row["distribution_id"]; ?></td>
<td>
<?php
$rating=(int)$row["rating"];
for($i=1;$i<=5;$i++){
    if($i<=$rating) echo "★";
    else echo "☆";
}
?>
<span style="margin-left:6px;"><?php echo $rating; ?>/5</span>
</td>
<td><?php echo $row["comments"]; ?></td>
<td><?php echo $row["date"]; ?></td>
<td><?php echo $row["status"]; ?></td>
<td>
<a href="deleteFeedback.php?id=<?php echo $row["feedback_id"]; ?>" 
class="btn"
onclick="return confirm('Are you sure you want to delete this feedback?');">
Delete
</a>
</td>
</tr>
<?php
}} 
else{
echo "<tr><td colspan='7'>No feedback records found.</td></tr>";}
?>
</table>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>