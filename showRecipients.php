<?php
require_once "DBconnect.php";
$sql="SELECT recipient_id, org_name, location, priority_level, capacity FROM Recipient_Organization";
$result=$conn->query($sql);
$location_sql = "SELECT location, COUNT(*) AS total FROM Recipient_Organization GROUP BY location";
$location_result = $conn->query($location_sql);
$locations = array();
$location_counts = array();
while($location_row = $location_result->fetch_assoc())
{
    $locations[] = $location_row["location"];
    $location_counts[] = $location_row["total"];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recipients</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<nav class="navbar">
    <div class="logo">ZERO HUNGER</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showDonors.php">Donors</a>
        <a href="showRecipients.php">Recipients</a>
    </div>
</nav>
<div class="container">
    <h1 class="page-title">Recipients</h1>
    <a href="addRecipient.php" class="btn">
        Add Recipient    </a>
    <a href="addRecipientRequirement.php" class="btn">
        Add Requirement
    </a>
    <a href="showRecipientRequirements.php" class="btn">
        View Requirements
    </a>
    <br><br>
    <table>
        <tr>
            <th>Recipient ID</th>
            <th>Name</th>
            <th>Location</th>
            <th>Priority Level</th>
            <th>Capacity</th>
        </tr>
        <?php
        if($result->num_rows>0)
        {
            while($row=$result->fetch_assoc())
            {
        ?>
        <tr>
            <td><?php echo $row["recipient_id"]; ?></td>
            <td><?php echo $row["org_name"]; ?></td>
            <td><?php echo $row["location"]; ?></td>
            <td><?php echo $row["priority_level"]; ?></td>
            <td><?php echo $row["capacity"]; ?></td>
        </tr>
        <?php
            }
        }
        else
        {
            echo "<tr>
                    <td colspan='5'>No recipients found.</td>
                  </tr>";
        }
        ?>
    </table>
    <br><br>
    <div class="recipient-chart">
        <h2>Recipient Distribution by Location</h2>
        <canvas id="recipientPieChart"></canvas>
    </div>
</div>
<script>
const locations =
<?php echo json_encode($locations); ?>;
const locationCounts =
<?php echo json_encode($location_counts); ?>;

new Chart(
    document.getElementById("recipientPieChart"),
    { type: "pie",
        data: {
            labels: locations,
            datasets: [{
                data: locationCounts
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
                    text: ""
                }
            }
        }
    }
);
</script>
</body>
</html>