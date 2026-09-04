<?php
require_once "DBconnect.php";
$sql="SELECT SUM(expiry_date < CURDATE()) AS Expired, SUM(expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(),INTERVAL 7 DAY)) AS ExpiringSoon, SUM(expiry_date > DATE_ADD(CURDATE(),INTERVAL 7 DAY)) AS Safe FROM Food_item";
$result=$conn->query($sql);
$row=$result->fetch_assoc();
$expired=$row["Expired"] ?? 0;
$expiring=$row["ExpiringSoon"] ?? 0;
$safe=$row["Safe"] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Food Expiry Summary</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
<h1 class="page-title">Food Expiry Summary</h1>
<div style="background:white;padding:25px;border-radius:12px;margin:25px 0;">
<canvas id="foodChart"></canvas>
</div>
<table>
<tr><th>Food Status</th>
<th>Total Items</th>
</tr>
<tr>
<td>Expired</td>
<td><?php echo $expired; ?></td></tr>
<tr>
<td>Expiring Soon</td>
<td><?php echo $expiring; ?></td>
</tr>
<tr>
<td>Safe</td>
<td><?php echo $safe; ?></td>
</tr>
</table>
<br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
<script>
new Chart(document.getElementById('foodChart'),{
    type:'bar',
    data:{
        labels:['Expired','Expiring Soon','Safe'],
        datasets:[{
            label:'Number of Food Items',
            data:[
                <?php echo $expired; ?>,
                <?php echo $expiring; ?>,
                <?php echo $safe; ?>
            ],
            backgroundColor:['#dc3545','#ffc107','#2f8f5b']
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
                    text:'Number of Food Items'
                }
            }
        }
    }
});
</script>
</body>
</html>
<?php $conn->close(); ?>