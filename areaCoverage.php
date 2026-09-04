<?php
require_once "DBconnect.php";
$sql="SELECT area, COUNT(*) AS TotalVolunteers FROM Volunteer GROUP BY area ORDER BY TotalVolunteers DESC";
$result=$conn->query($sql);
$areas=[];
$totals=[];
$rows=[];
$maxVolunteers=0;
while($row=$result->fetch_assoc()){
    $rows[]=$row;
    $areas[]=$row["area"];
    $totals[]=$row["TotalVolunteers"];
    if($row["TotalVolunteers"]>$maxVolunteers){
        $maxVolunteers=$row["TotalVolunteers"];
    }}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Volunteer Area Coverage</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
<h1 class="page-title">Volunteer Area Coverage</h1>
<h2>Volunteer Coverage by Area</h2>
<div style="background:white; padding:25px; border-radius:12px; margin:25px 0;">
<canvas id="areaChart"></canvas>
</div>
<table>
<tr>
<th>Area</th>
<th>Total Volunteers</th>
<th>Status</th>
</tr>
<?php foreach($rows as $row){ 
$isHighest=$row["TotalVolunteers"]==$maxVolunteers;
?>
<tr <?php if($isHighest) echo 'style="background-color:#d4edda;font-weight:bold;"'; ?>>
<td><?php echo htmlspecialchars($row["area"]); ?></td>
<td><?php echo $row["TotalVolunteers"]; ?></td>
<td>
<?php
if($isHighest){
    echo "Highest Coverage";
}else{
    echo "Normal";
}
?>
</td>
</tr>
<?php } ?>
</table>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
<script>
const ctx=document.getElementById('areaChart');
new Chart(ctx,{
    type:'bar',
    data:{
        labels:<?php echo json_encode($areas); ?>,
        datasets:[{
            label:'Total Volunteers',
            data:<?php echo json_encode($totals); ?>,
            backgroundColor:'#407057'
        }]
    },
    options:{
        responsive:true,
        scales:{
            y:{
                beginAtZero:true,
                ticks:{stepSize:1},
                title:{
                    display:true,
                    text:'Number of Volunteers'
                }
            },
            x:{
                title:{
                    display:true,
                    text:'Area'
                }
            }
        }
    }
});
</script>
</body>
</html>
<?php $conn->close(); ?>