<?php

require_once "DBconnect.php";
$sql = "SELECT ro.recipient_id,ro.org_name, ro.location, ro.priority_level, ro.capacity, COUNT(a.allocation_id) AS total_allocations, SUM(a.quantity) AS total_quantity
 FROM Recipient_Organization ro JOIN Resource_Allocation a ON ro.recipient_id = a.recipient_id  GROUP BY ro.recipient_id, ro.org_name,ro.location,
ro.priority_level,ro.capacity ORDER BY total_quantity DESC";

$result = $conn->query($sql);

$allocationRows = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $allocationRows[] = $row;
    }
}

$allocationIdsByRecipient = array();
$idSql = "SELECT allocation_id FROM Resource_Allocation WHERE recipient_id = ? ORDER BY allocation_id";
$idStmt = $conn->prepare($idSql);

foreach ($allocationRows as $allocationRow) {
    $recipientId = $allocationRow["recipient_id"];
    $idStmt->bind_param("i", $recipientId);
    $idStmt->execute();
    $idResult = $idStmt->get_result();

    $ids = array();
    while ($idRow = $idResult->fetch_assoc()) {
        $ids[] = $idRow["allocation_id"];
    }

    $allocationIdsByRecipient[$recipientId] = $ids;
}
$idStmt->close();

$allocationKpiSql = "SELECT COUNT(*) AS total_allocations, SUM(quantity) AS total_allocated_quantity, COUNT(DISTINCT recipient_id) AS recipients_served
FROM Resource_Allocation";
$allocationKpiResult = $conn->query($allocationKpiSql);
$allocationKpi = array(
    "total_allocations" => 0,
    "total_allocated_quantity" => 0,
    "recipients_served" => 0
);
if ($allocationKpiResult && $allocationKpiResult->num_rows > 0) {
    $allocationKpi = $allocationKpiResult->fetch_assoc();
}

$highPrioritySql = "SELECT COUNT(DISTINCT r.recipient_id) AS high_priority_recipients FROM Recipient_Organization r JOIN Resource_Allocation a
ON r.recipient_id = a.recipient_id WHERE r.priority_level = 'High'";
$highPriorityResult = $conn->query($highPrioritySql);
$highPriorityRecipients = 0;
if ($highPriorityResult && $highPriorityResult->num_rows > 0) {
    $highPriorityRecipients = (int)$highPriorityResult->fetch_assoc()["high_priority_recipients"];
}


$prioritySql = "SELECT r.priority_level, COUNT(a.allocation_id) AS total_allocations, SUM(a.quantity) AS total_quantity FROM Recipient_Organization r
JOIN Resource_Allocation a ON r.recipient_id = a.recipient_id GROUP BY r.priority_level ORDER BY total_quantity DESC";
$priorityResult = $conn->query($prioritySql);
$priorityLabels = array();
$priorityValues = array();
$priorityRows = array();

$recipientChartLabels = array();
$recipientChartValues = array();
if ($priorityResult) {
    while ($row = $priorityResult->fetch_assoc()) {
        $priorityRows[] = $row;
        $priorityLabels[] = $row["priority_level"];
        $priorityValues[] = (int)$row["total_quantity"];
    }
}

foreach ($allocationRows as $allocationRow) {
    $recipientChartLabels[] = $allocationRow["org_name"];
    $recipientChartValues[] = (int)$allocationRow["total_allocations"];
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Resource Allocation Analysis</title>

    <link rel="stylesheet" href="css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .dashboard-section {
            margin-top: 70px;
            padding-top: 45px;
            border-top: 3px solid #dfe9e2;
        }

        .dashboard-heading {
            font-family: Georgia, "Times New Roman", serif;
            color: #1f6f43;
            font-size: 30px;
            margin-bottom: 8px;
        }

        .dashboard-subtitle {
            color: #68736b;
            margin-bottom: 25px;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin: 25px 0 30px;
        }

        .kpi-card {
            background: white;
            border: 1px solid #e4ebe5;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);
        }

        .kpi-label {
            color: #68736b;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .kpi-value {
            color: #1f6f43;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 31px;
            font-weight: bold;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border: 1px solid #e4ebe5;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);
            min-height: 340px;
        }

        .chart-card h3 {
            margin-bottom: 15px;
            font-family: Georgia, "Times New Roman", serif;
        }

        .chart-box {
            position: relative;
            height: 260px;
        }

        .section-note {
            background: #eef8f1;
            border-left: 5px solid #2f8f5b;
            padding: 14px 16px;
            border-radius: 7px;
            margin: 20px 0;
            color: #315442;
        }

        .analytics-table {
            margin-top: 25px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 12px;
            background: #eef8f1;
            color: #246b45;
            font-size: 12px;
            font-weight: bold;
        }

        @media (max-width: 850px) {
            .kpi-grid,
            .chart-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 550px) {
            .kpi-grid,
            .chart-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

</head>

<body>

<nav class="navbar">

    <div class="logo">ZERO HUNGER</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showMission.php">Rescue Missions</a>
        <a href="showAllocations.php">Resource Allocation</a>
        <a href="showImpact.php">Community Impact</a>
        <a href="showPartners.php">Partners</a>
    </div>

</nav>

<div class="container">
    <h1 class="page-title">
        Food Rescue & Resource Matching
    </h1>

    <p>
        Allocate rescued food to recipient organizations,
        analyze resource requirements and prioritize food distribution.
    </p>

    <div class="feature-buttons">
        <a href="addAllocation.php" class="btn">Add Allocation</a>
        <a href="showAllocations.php" class="btn">View Allocations</a>
        <a href="priorityMatching.php" class="btn">Priority & Matching Analysis</a>
    </div>

    <br><br>

    <p>
        This report shows the total number of allocations
        and total quantity allocated to each recipient organization.
    </p>

    <table>

        <tr>
            <th>Recipient ID</th>
            <th>Recipient Organization</th>
            <th>Total Allocations</th>
            <th>Total Quantity</th>
            <th>Actions</th>
        </tr>

        <?php if (count($allocationRows) > 0) { ?>
            <?php foreach ($allocationRows as $row) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["recipient_id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["org_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["total_allocations"]); ?></td>
                    <td><?php echo htmlspecialchars($row["total_quantity"]); ?></td>
                    <td>
                        <?php
                        $recipientId = $row["recipient_id"];
                        $allocationIds = isset($allocationIdsByRecipient[$recipientId])
                            ? $allocationIdsByRecipient[$recipientId]
                            : array();
                        foreach ($allocationIds as $allocationId) {
                        ?>
                            <a href="editAllocation.php?allocation_id=<?php echo $allocationId; ?>" class="btn">Edit</a>
                            <a href="deleteAllocation.php?allocation_id=<?php echo $allocationId; ?>"
                               class="btn"
                               onclick="return confirm('Are you sure you want to delete this allocation?');">Delete</a>
                            <br><br>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">No allocation data found.</td>
            </tr>
        <?php } ?>

    </table>


    <section class="dashboard-section">

        <h2 class="dashboard-heading">Intelligent Resource Allocation Dashboard</h2>

        <p class="dashboard-subtitle">
            Visualize how rescued food is allocated across recipient organizations,
            including priority-based allocation.
        </p>

        <div class="section-note">
            The original allocation table and all four existing buttons remain above.
            This section adds the analytical and visual layer for the Intelligent Food
            Rescue and Resource Matching feature.
        </div>

        <div class="kpi-grid">

            <div class="kpi-card">
                <div class="kpi-label">Total Allocations</div>
                <div class="kpi-value"><?php echo (int)$allocationKpi["total_allocations"]; ?></div>
            </div>

            <div class="kpi-card">
                <div class="kpi-label">Food Allocated</div>
                <div class="kpi-value"><?php echo (int)$allocationKpi["total_allocated_quantity"]; ?></div>
            </div>

            <div class="kpi-card">
                <div class="kpi-label">Recipients Served</div>
                <div class="kpi-value"><?php echo (int)$allocationKpi["recipients_served"]; ?></div>
            </div>

            <div class="kpi-card">
                <div class="kpi-label">High Priority Recipients Served</div>
                <div class="kpi-value"><?php echo $highPriorityRecipients; ?></div>
            </div>

        </div>

        <div class="chart-grid">

            <div class="chart-card">
                <h3>Allocated Quantity by Priority</h3>
                <div class="chart-box">
                    <canvas id="priorityChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>Allocation Count by Recipient</h3>
                <div class="chart-box">
                    <canvas id="allocationOverviewChart"></canvas>
                </div>
            </div>

        </div>

    </section>

</div>

<script>

const priorityLabels = <?php echo json_encode($priorityLabels); ?>;
const priorityValues = <?php echo json_encode($priorityValues); ?>;

new Chart(document.getElementById('priorityChart'), {
    type: 'bar',
    data: {
        labels: priorityLabels,
        datasets: [{
            label: 'Allocated Quantity',
            data: priorityValues
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

const recipientChartLabels = <?php echo json_encode($recipientChartLabels); ?>;
const recipientChartValues = <?php echo json_encode($recipientChartValues); ?>;

new Chart(document.getElementById('allocationOverviewChart'), {
    type: 'bar',
    data: {
        labels: recipientChartLabels,
        datasets: [{
            label: 'Number of Allocations',
            data: recipientChartValues
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>

<?php
$conn->close();
?>

</body>
</html>
