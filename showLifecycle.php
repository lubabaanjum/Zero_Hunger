<?php
require_once "DBconnect.php";
$sql="SELECT fd.donation_id, fd.pickup_location,fd.donation_date, fd.status AS donation_status, ra.allocation_id,
ra.quantity,ra.date AS allocation_date, d.distribution_id,d.pickup_time,d.status AS distribution_status,d.delivery_time,
rm.mission_id,rm.mission_name,rm.mission_date,rm.status AS mission_status FROM Food_donation fd LEFT JOIN Resource_Allocation 
ra ON fd.donation_id=ra.donation_id LEFT JOIN Distribution d ON ra.allocation_id=d.allocation_id 
LEFT JOIN Rescue_Mission rm ON d.mission_id=rm.mission_id ORDER BY fd.donation_id DESC";

$result=$conn->query($sql);
$status_sql = "SELECT status, COUNT(*) AS total FROM Distribution GROUP BY status";
$status_result = $conn->query($status_sql);
$statuses = array();
$status_counts = array();
while($status_row = $status_result->fetch_assoc())
{
    $statuses[] = $status_row["status"];
    $status_counts[] = $status_row["total"];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Distribution Lifecycle</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<nav class="navbar">
    <div class="logo">ZERO HUNGER</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="distributionMenu.php">Distribution Menu</a>
    </div>
</nav>
<div class="container">
    <h1 class="page-title">End-to-End Distribution Lifecycle</h1>
    <div class="lifecycle-chart">
        <h2>Distribution Status Overview</h2>
        <canvas id="distributionStatusChart"></canvas>
    </div>
    <br><br>
    <table>
        <tr>
            <th>Donation ID</th>
            <th>Donation Date</th>
            <th>Donation Status</th>
            <th>Allocation ID</th>
            <th>Quantity</th>
            <th>Distribution ID</th>
            <th>Pickup Time</th>
            <th>Distribution Status</th>
            <th>Delivery Time</th>
            <th>Mission</th>
        </tr>
        <?php
        if($result->num_rows>0)
        {
            while($row=$result->fetch_assoc())
            {
        ?>
        <tr>
            <td><?php echo $row["donation_id"]; ?></td>
            <td><?php echo $row["donation_date"]; ?></td>
            <td><?php echo $row["donation_status"]; ?></td>
            <td><?php echo $row["allocation_id"]; ?></td>
            <td><?php echo $row["quantity"]; ?></td>
            <td><?php echo $row["distribution_id"]; ?></td>
            <td><?php echo $row["pickup_time"]; ?></td>
            <td><?php echo $row["distribution_status"]; ?></td>
            <td><?php echo $row["delivery_time"]; ?></td>
            <td><?php echo $row["mission_name"]; ?></td>
        </tr>
        <?php
            }
        }
        else
        {
            echo "<tr>
                    <td colspan='10'>
                    No distribution lifecycle records found.
                    </td>
                  </tr>";
        }
        ?>
    </table>
</div>
<script>
const statuses =
<?php echo json_encode($statuses); ?>;
const statusCounts =
<?php echo json_encode($status_counts); ?>;
new Chart(
    document.getElementById("distributionStatusChart"),
    {
        type: "pie",
        data: {
            labels: statuses,
            datasets: [{
                data: statusCounts
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom"
                },
                title: {
                    display: true,
                    text: "Distribution Status"
                }
            }
        }
    }
);
</script>
</body>
</html>