<?php

require_once "DBconnect.php";

/* =========================================================
   EXISTING IMPACT QUERY - KEPT FOR THE ORIGINAL TABLE
   ========================================================= */
$sql = "SELECT
            d.donation_id,
            d.donation_date,
            d.status,
            f.item_id,
            f.item_name,
            f.quantity
        FROM Food_donation d
        JOIN Food_item f
        ON d.donation_id = f.donation_id";

$result = $conn->query($sql);

$impactRows = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $impactRows[] = $row;
    }
}

/* =========================================================
   NEW IMPACT DASHBOARD QUERIES
   ========================================================= */

$impactKpiSql = "SELECT
                    COUNT(DISTINCT d.donation_id) AS total_donations,
                    COUNT(DISTINCT fi.item_id) AS total_food_items,
                    COALESCE(SUM(fi.quantity), 0) AS total_food_quantity
                 FROM Food_Donation d
                 LEFT JOIN Food_Item fi
                    ON d.donation_id = fi.donation_id";
$impactKpiResult = $conn->query($impactKpiSql);
$impactKpi = array(
    "total_donations" => 0,
    "total_food_items" => 0,
    "total_food_quantity" => 0
);
if ($impactKpiResult && $impactKpiResult->num_rows > 0) {
    $impactKpi = $impactKpiResult->fetch_assoc();
}

$distributionKpiSql = "SELECT
                            COUNT(*) AS total_distributions,
                            SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_distributions,
                            SUM(CASE WHEN status = 'In Transit' THEN 1 ELSE 0 END) AS transit_distributions,
                            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_distributions
                        FROM Distribution";
$distributionKpiResult = $conn->query($distributionKpiSql);
$distributionKpi = array(
    "total_distributions" => 0,
    "completed_distributions" => 0,
    "transit_distributions" => 0,
    "pending_distributions" => 0
);
if ($distributionKpiResult && $distributionKpiResult->num_rows > 0) {
    $distributionKpi = $distributionKpiResult->fetch_assoc();
}

$distributionStatusSql = "SELECT status, COUNT(*) AS total_distributions
                          FROM Distribution
                          GROUP BY status
                          ORDER BY total_distributions DESC";
$distributionStatusResult = $conn->query($distributionStatusSql);
$distributionStatusLabels = array();
$distributionStatusValues = array();
if ($distributionStatusResult) {
    while ($row = $distributionStatusResult->fetch_assoc()) {
        $distributionStatusLabels[] = $row["status"];
        $distributionStatusValues[] = (int)$row["total_distributions"];
    }
}

/* Donor -> Food_Donation -> Food_Item */
$donorImpactSql = "SELECT
                      d.donor_id,
                      d.name,
                      COUNT(DISTINCT fd.donation_id) AS total_donations,
                      COUNT(fi.item_id) AS total_food_items,
                      COALESCE(SUM(fi.quantity), 0) AS total_food_quantity
                   FROM Donor d
                   JOIN Food_Donation fd
                       ON d.donor_id = fd.donor_id
                   LEFT JOIN Food_Item fi
                       ON fd.donation_id = fi.donation_id
                   GROUP BY d.donor_id, d.name
                   HAVING COUNT(DISTINCT fd.donation_id) >= 1
                   ORDER BY total_food_quantity DESC";
$donorImpactResult = $conn->query($donorImpactSql);
$donorLabels = array();
$donorValues = array();
$donorImpactRows = array();
if ($donorImpactResult) {
    while ($row = $donorImpactResult->fetch_assoc()) {
        $donorImpactRows[] = $row;
        $donorLabels[] = $row["name"];
        $donorValues[] = (int)$row["total_food_quantity"];
    }
}

/* Mission -> Distribution -> Allocation -> Donation -> Food Item */
$missionImpactSql = "SELECT
                        m.mission_id,
                        m.mission_name,
                        m.mission_date,
                        m.status,
                        COUNT(DISTINCT d.distribution_id) AS total_distributions,
                        COUNT(DISTINCT a.allocation_id) AS total_allocations,
                        COALESCE(SUM(a.quantity), 0) AS total_allocated_quantity
                     FROM Rescue_Mission m
                     LEFT JOIN Distribution d
                        ON m.mission_id = d.mission_id
                     LEFT JOIN Resource_Allocation a
                        ON d.allocation_id = a.allocation_id
                     GROUP BY
                        m.mission_id,
                        m.mission_name,
                        m.mission_date,
                        m.status
                     HAVING COUNT(DISTINCT d.distribution_id) > 0
                     ORDER BY total_allocated_quantity DESC";
$missionImpactResult = $conn->query($missionImpactSql);
$missionLabels = array();
$missionValues = array();
$missionImpactRows = array();
if ($missionImpactResult) {
    while ($row = $missionImpactResult->fetch_assoc()) {
        $missionImpactRows[] = $row;
        $missionLabels[] = $row["mission_name"];
        $missionValues[] = (int)$row["total_allocated_quantity"];
    }
}

/* Donation trend by date */
$impactTrendSql = "SELECT
                      donation_date,
                      COALESCE(SUM(fi.quantity), 0) AS total_food_quantity
                   FROM Food_Donation fd
                   LEFT JOIN Food_Item fi
                      ON fd.donation_id = fi.donation_id
                   GROUP BY donation_date
                   ORDER BY donation_date ASC";
$impactTrendResult = $conn->query($impactTrendSql);
$trendLabels = array();
$trendValues = array();
if ($impactTrendResult) {
    while ($row = $impactTrendResult->fetch_assoc()) {
        $trendLabels[] = $row["donation_date"];
        $trendValues[] = (int)$row["total_food_quantity"];
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Community Impact</title>

    <link rel="stylesheet" href="css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* =====================================================
           NEW VISUAL SECTION - ORIGINAL CONTENT REMAINS ABOVE
           ===================================================== */
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

        .analytics-table {
            margin-top: 25px;
        }

        .section-note {
            background: #eef8f1;
            border-left: 5px solid #2f8f5b;
            padding: 14px 16px;
            border-radius: 7px;
            margin: 20px 0;
            color: #315442;
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
        <a href="showImpact.php">Community Impact</a>
        <a href="showAllocations.php">Resource Allocation</a>
        <a href="showPartners.php">Partners</a>
    </div>

</nav>

<div class="container">

    <!-- =====================================================
         EXISTING PAGE - KEPT
         ===================================================== -->

    <h1 class="page-title">
        Sustainability & Community Impact
    </h1>

    <p>
        Analyze rescue missions, food donations, distributions,
        volunteers, recipients and overall community impact.
    </p>

    <div class="feature-buttons">
        <a href="impactSummary.php" class="btn">Impact Summary</a>
        <a href="impactDonors.php" class="btn">Donor Impact</a>
        <a href="impactItems.php" class="btn">Food Item Impact</a>
        <a href="impactMission.php" class="btn">Mission Impact</a>
        <a href="impactRecipients.php" class="btn">Recipient Impact</a>
        <a href="impactVolunteers.php" class="btn">Volunteer Impact</a>
        <a href="impactDistribution.php" class="btn">Distribution Impact</a>
    </div>

    <br><br>

    <table>

        <tr>
            <th>Donation ID</th>
            <th>Donation Date</th>
            <th>Status</th>
            <th>Item ID</th>
            <th>Food Item</th>
            <th>Quantity</th>
        </tr>

        <?php if (count($impactRows) > 0) { ?>
            <?php foreach ($impactRows as $row) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["donation_id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["donation_date"]); ?></td>
                    <td><?php echo htmlspecialchars($row["status"]); ?></td>
                    <td><?php echo htmlspecialchars($row["item_id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["item_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6">No donation and food item records found.</td>
            </tr>
        <?php } ?>

    </table>


    <!-- =====================================================
         NEW SECOND SECTION / PAGE-LIKE DASHBOARD
         ===================================================== -->

    <section class="dashboard-section">

        <h2 class="dashboard-heading">Community Impact Analytics Dashboard</h2>

        <p class="dashboard-subtitle">
            Visual analysis of rescued food, donors, distributions and mission impact.
        </p>

        <div class="section-note">
            The original impact table and all existing Impact buttons remain above.
            The dashboard below is an additional visual layer for the Sustainability
            & Community Impact feature.
        </div>

        <div class="kpi-grid">

            <div class="kpi-card">
                <div class="kpi-label">Total Donations</div>
                <div class="kpi-value"><?php echo (int)$impactKpi["total_donations"]; ?></div>
            </div>

            <div class="kpi-card">
                <div class="kpi-label">Food Items</div>
                <div class="kpi-value"><?php echo (int)$impactKpi["total_food_items"]; ?></div>
            </div>

            <div class="kpi-card">
                <div class="kpi-label">Food Quantity Rescued</div>
                <div class="kpi-value"><?php echo (int)$impactKpi["total_food_quantity"]; ?></div>
            </div>

            <div class="kpi-card">
                <div class="kpi-label">Completed Distributions</div>
                <div class="kpi-value"><?php echo (int)$distributionKpi["completed_distributions"]; ?></div>
            </div>

        </div>

        <div class="chart-grid">

            <div class="chart-card">
                <h3>Distribution Status</h3>
                <div class="chart-box">
                    <canvas id="distributionStatusChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>Food Quantity by Donor</h3>
                <div class="chart-box">
                    <canvas id="donorImpactChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>Mission Impact</h3>
                <div class="chart-box">
                    <canvas id="missionImpactChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>Food Rescue Trend</h3>
                <div class="chart-box">
                    <canvas id="impactTrendChart"></canvas>
                </div>
            </div>

        </div>

        <h2 class="dashboard-heading">Mission Impact Analysis</h2>

        <p class="dashboard-subtitle">
            This analysis follows the actual database relationship from a rescue
            mission to its distribution records and resource allocations.
        </p>

        <table class="analytics-table">
            <tr>
                <th>Mission</th>
                <th>Mission Date</th>
                <th>Status</th>
                <th>Distributions</th>
                <th>Allocations</th>
                <th>Total Allocated Quantity</th>
            </tr>

            <?php if (count($missionImpactRows) > 0) { ?>
                <?php foreach ($missionImpactRows as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["mission_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["mission_date"]); ?></td>
                        <td><?php echo htmlspecialchars($row["status"]); ?></td>
                        <td><?php echo (int)$row["total_distributions"]; ?></td>
                        <td><?php echo (int)$row["total_allocations"]; ?></td>
                        <td><?php echo (int)$row["total_allocated_quantity"]; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6">No mission impact data found.</td>
                </tr>
            <?php } ?>
        </table>

        <br><br>

        <h2 class="dashboard-heading">Donor Contribution Analysis</h2>

        <table class="analytics-table">
            <tr>
                <th>Donor</th>
                <th>Total Donations</th>
                <th>Food Items</th>
                <th>Total Food Quantity</th>
            </tr>

            <?php if (count($donorImpactRows) > 0) { ?>
                <?php foreach ($donorImpactRows as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo (int)$row["total_donations"]; ?></td>
                        <td><?php echo (int)$row["total_food_items"]; ?></td>
                        <td><?php echo (int)$row["total_food_quantity"]; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">No donor impact data found.</td>
                </tr>
            <?php } ?>
        </table>

    </section>

</div>

<script>
const distributionStatusLabels = <?php echo json_encode($distributionStatusLabels); ?>;
const distributionStatusValues = <?php echo json_encode($distributionStatusValues); ?>;
const donorLabels = <?php echo json_encode($donorLabels); ?>;
const donorValues = <?php echo json_encode($donorValues); ?>;
const missionLabels = <?php echo json_encode($missionLabels); ?>;
const missionValues = <?php echo json_encode($missionValues); ?>;
const trendLabels = <?php echo json_encode($trendLabels); ?>;
const trendValues = <?php echo json_encode($trendValues); ?>;

new Chart(document.getElementById('distributionStatusChart'), {
    type: 'pie',
    data: {
        labels: distributionStatusLabels,
        datasets: [{
            data: distributionStatusValues
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('donorImpactChart'), {
    type: 'bar',
    data: {
        labels: donorLabels,
        datasets: [{
            label: 'Food Quantity',
            data: donorValues
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

new Chart(document.getElementById('missionImpactChart'), {
    type: 'bar',
    data: {
        labels: missionLabels,
        datasets: [{
            label: 'Allocated Quantity',
            data: missionValues
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

new Chart(document.getElementById('impactTrendChart'), {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [{
            label: 'Food Quantity',
            data: trendValues,
            tension: 0.25,
            fill: false
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
</script>

<?php
$conn->close();
?>

</body>
</html>
