<?php
require_once "DBconnect.php";

$sql="SELECT ro.org_name,AVG(f.rating) AS AvgRating,COUNT(f.feedback_id) AS TotalFeedback FROM Recipient_Organization ro
JOIN Feedback f ON ro.recipient_id=f.recipient_id
GROUP BY ro.recipient_id,ro.org_name ORDER BY AvgRating DESC";
$result=$conn->query($sql);
$organizations=[];
$ratings=[];
$rows=[];
while($row=$result->fetch_assoc()){
    $rows[]=$row;
    $organizations[]=$row["org_name"];
    $ratings[]=round($row["AvgRating"],2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Feedback Summary</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
<h1 class="page-title">Feedback Summary</h1>
<h2>Average Rating by Recipient Organization</h2>
<div style="background:white;padding:25px;border-radius:12px;margin:25px 0;">
<canvas id="feedbackChart"></canvas>
</div>
<table>
<tr>
<th>Recipient Organization</th>
<th>Average Rating</th>
<th>Total Feedback</th>
<th>Satisfaction</th>
</tr>
<?php foreach($rows as $row){ 
$avg=round($row["AvgRating"],2);
if($avg>=4.5){
    $status="Excellent";
    $class="satisfaction-excellent";
}
elseif($avg>=4){
    $status="Very Good";
    $class="satisfaction-verygood";
}
elseif($avg>=3){
    $status="Good";
    $class="satisfaction-good";
}
elseif($avg>=2){
    $status="Needs Improvement";
    $class="satisfaction-needs";
}
else{
    $status="Poor";
    $class="satisfaction-poor";
}
?>
<tr>
<td><?php echo htmlspecialchars($row["org_name"]); ?></td>
<td><?php echo number_format($avg,2); ?> / 5</td>
<td><?php echo $row["TotalFeedback"]; ?></td>
<td><span class="<?php echo $class; ?>"><?php echo $status; ?></span></td>
</tr>
<?php } ?>
</table>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
<script>
new Chart(document.getElementById('feedbackChart'),{
    type:'bar',
    data:{
        labels:<?php echo json_encode($organizations); ?>,
        datasets:[{
            label:'Average Rating',
            data:<?php echo json_encode($ratings); ?>,
            backgroundColor:'#2f8f5b'
        }]
    },
    options:{
        responsive:true,
        scales:{
            y:{
                beginAtZero:true,
                max:5,
                ticks:{stepSize:1}
            }
        }
    }
});
</script>
</body>
</html>
<?php $conn->close(); ?>