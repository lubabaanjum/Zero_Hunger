<?php
require_once "DBconnect.php";
$sql = "SELECT d.donation_id, d.donation_date,d.status,f.item_id,f.item_name,f.quantity
FROM Food_Donation d JOIN Food_Item f ON d.donation_id = f.donation_id";

$result = $conn->query($sql);

$impactRows = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $impactRows[] = $row;
    }
}

$totalDonationResult = $conn->query("SELECT COUNT(*) AS total FROM Food_Donation");
$totalDonations = 0;
if ($totalDonationResult && $totalDonationResult->num_rows > 0) {
    $totalDonations = (int)$totalDonationResult->fetch_assoc()["total"];
}

$totalFoodItemResult = $conn->query("SELECT COUNT(*) AS total FROM Food_Item");
$totalFoodItems = 0;
if ($totalFoodItemResult && $totalFoodItemResult->num_rows > 0) {
    $totalFoodItems = (int)$totalFoodItemResult->fetch_assoc()["total"];
}

$totalFoodQuantityResult = $conn->query("SELECT SUM(quantity) AS total FROM Food_Item");
$totalFoodQuantity = 0;
if ($totalFoodQuantityResult && $totalFoodQuantityResult->num_rows > 0) {
    $value = $totalFoodQuantityResult->fetch_assoc()["total"];
    $totalFoodQuantity = $value === null ? 0 : (int)$value;
}

$completedDistributionResult = $conn->query("SELECT COUNT(*) AS total FROM Distribution WHERE status = 'Completed'");
$completedDistributions = 0;
if ($completedDistributionResult && $completedDistributionResult->num_rows > 0) {
    $completedDistributions = (int)$completedDistributionResult->fetch_assoc()["total"];
}

$distributionStatusResult = $conn->query("SELECT status, COUNT(*) AS total_distributions
                                           FROM Distribution
                                           GROUP BY status
                                           ORDER BY total_distributions DESC");
$distributionStatusRows = array();
$distributionStatusTotal = 0;
if ($distributionStatusResult) {
    while ($row = $distributionStatusResult->fetch_assoc()) {
        $distributionStatusRows[] = $row;
        $distributionStatusTotal += (int)$row["total_distributions"];
    }
}


$donorImpactSql = "SELECT d.donor_id,d.name,COUNT(fd.donation_id) AS total_donations,COUNT(fi.item_id) AS total_food_items,SUM(fi.quantity) AS total_food_quantity
FROM Donor d JOIN Food_Donation fd ON d.donor_id = fd.donor_id JOIN Food_Item fi ON fd.donation_id = fi.donation_id
GROUP BY d.donor_id, d.name ORDER BY total_food_quantity DESC";
$donorImpactResult = $conn->query($donorImpactSql);
$donorImpactRows = array();
$donorImpactMax = 0;
if ($donorImpactResult) {
    while ($row = $donorImpactResult->fetch_assoc()) {
        $donorImpactRows[] = $row;
        if ((int)$row["total_food_quantity"] > $donorImpactMax) {
            $donorImpactMax = (int)$row["total_food_quantity"];
        }
    }
}

$missionImpactSql = "SELECT m.mission_id,m.mission_name,m.mission_date,m.status,COUNT(d.distribution_id) AS total_distributions,
COUNT(a.allocation_id) AS total_allocations,SUM(a.quantity) AS total_allocated_quantity FROM Rescue_Mission m JOIN Distribution d
ON m.mission_id = d.mission_id JOIN Resource_Allocation a ON d.allocation_id = a.allocation_id GROUP BY m.mission_id,m.mission_name,
m.mission_date,m.status ORDER BY total_allocated_quantity DESC";
$missionImpactResult = $conn->query($missionImpactSql);
$missionImpactRows = array();
$missionImpactMax = 0;
if ($missionImpactResult) {
    while ($row = $missionImpactResult->fetch_assoc()) {
        $missionImpactRows[] = $row;
        if ((int)$row["total_allocated_quantity"] > $missionImpactMax) {
            $missionImpactMax = (int)$row["total_allocated_quantity"];
        }
    }
}

$impactTrendResult = $conn->query("SELECT fd.donation_date, SUM(fi.quantity) AS total_food_quantity
                                   FROM Food_Donation fd
                                   JOIN Food_Item fi
                                   ON fd.donation_id = fi.donation_id
                                   GROUP BY fd.donation_date
                                   ORDER BY fd.donation_date ASC");
$impactTrendRows = array();
$trendMax = 0;
if ($impactTrendResult) {
    while ($row = $impactTrendResult->fetch_assoc()) {
        $impactTrendRows[] = $row;
        if ((int)$row["total_food_quantity"] > $trendMax) {
            $trendMax = (int)$row["total_food_quantity"];
        }
    }
}

$pieColors = array("#2f8f5b", "#f0a64b", "#5b7cfa", "#b66dff", "#e56b6f");
$pieParts = array();
$pieStart = 0;
foreach ($distributionStatusRows as $index => $row) {
    $value = (int)$row["total_distributions"];
    $percentage = $distributionStatusTotal > 0 ? ($value / $distributionStatusTotal) * 100 : 0;
    $pieEnd = $pieStart + $percentage;
    $color = $pieColors[$index % count($pieColors)];
    $pieParts[] = $color . " " . $pieStart . "% " . $pieEnd . "%";
    $pieStart = $pieEnd;
}
$distributionPieGradient = count($pieParts) > 0 ? implode(", ", $pieParts) : "#e8ece9 0% 100%";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Community Impact</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-section { margin-top:70px; padding-top:45px; border-top:3px solid #dfe9e2; }
        .dashboard-heading { font-family:Georgia,"Times New Roman",serif; color:#1f6f43; font-size:30px; margin-bottom:8px; }
        .dashboard-subtitle { color:#68736b; margin-bottom:25px; }
        .section-note { background:#eef8f1; border-left:5px solid #2f8f5b; padding:14px 16px; border-radius:7px; margin:20px 0; color:#315442; }
        .kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin:25px 0 30px; }
        .kpi-card { background:white; border:1px solid #e4ebe5; border-radius:12px; padding:22px; box-shadow:0 5px 18px rgba(0,0,0,.06); }
        .kpi-label { color:#68736b; font-size:14px; margin-bottom:5px; }
        .kpi-value { color:#1f6f43; font-family:Georgia,"Times New Roman",serif; font-size:31px; font-weight:bold; }
        .chart-grid { display:grid; grid-template-columns:1fr 1fr; gap:25px; margin-bottom:30px; }
        .chart-card { background:white; border:1px solid #e4ebe5; border-radius:12px; padding:22px; box-shadow:0 5px 18px rgba(0,0,0,.06); min-height:340px; }
        .chart-card h3 { margin-bottom:15px; font-family:Georgia,"Times New Roman",serif; }
        .bar-row { margin:14px 0; }
        .bar-label { display:flex; justify-content:space-between; gap:12px; margin-bottom:6px; font-size:14px; }
        .bar-track { height:24px; background:#edf2ee; border-radius:12px; overflow:hidden; }
        .bar-fill { height:100%; background:#2f8f5b; border-radius:12px; min-width:2px; }
        .chart-card.large-chart { grid-column:1 / -1; min-height:460px; }
        .chart-card.large-chart h3 { text-align:center; font-size:24px; }
        .pie-layout { display:flex; align-items:center; justify-content:center; gap:55px; flex-wrap:wrap; min-height:350px; }
        .pie-chart { width:300px; height:300px; border-radius:50%; background:conic-gradient(<?php echo $distributionPieGradient; ?>); }
        .legend { margin:0; padding:0; list-style:none; }
        .legend li { margin:9px 0; }
        .legend-dot { display:inline-block; width:12px; height:12px; border-radius:50%; margin-right:7px; }
        .line-chart { width:100%; height:350px; border:1px solid #e1e8e2; background:#fafcfb; border-radius:8px; }
        .line-chart svg { width:100%; height:100%; }
        .chart-axis-label { fill:#68736b; font-size:11px; }
        .chart-grid-line { stroke:#dfe7e1; stroke-width:1; }
        .chart-line { fill:none; stroke:#2f8f5b; stroke-width:3; }
        .chart-point { fill:#2f8f5b; }
        .analytics-table { margin-top:25px; }
        .status-badge { display:inline-block; padding:4px 9px; border-radius:12px; background:#eef8f1; color:#246b45; font-size:12px; font-weight:bold; }
        @media(max-width:850px){ .kpi-grid,.chart-grid{grid-template-columns:1fr 1fr;} }
        @media(max-width:550px){ .kpi-grid,.chart-grid{grid-template-columns:1fr;} .pie-chart{width:220px;height:220px;} }
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
    <h1 class="page-title">Sustainability & Community Impact</h1>
    <p>Analyze rescue missions, food donations, distributions, volunteers, recipients and overall community impact.</p>

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
        <tr><th>Donation ID</th><th>Donation Date</th><th>Status</th><th>Item ID</th><th>Food Item</th><th>Quantity</th></tr>
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
            <tr><td colspan="6">No donation and food item records found.</td></tr>
        <?php } ?>
    </table>

    <section class="dashboard-section">
        <h2 class="dashboard-heading">Community Impact Analytics Dashboard</h2>
        <p class="dashboard-subtitle">Visual analysis of rescued food, donors, distributions and mission impact.</p>
        <div class="section-note">This is an additional visual section below the original impact page. Data is loaded directly by PHP from MySQL using raw SQL. No AJAX, JSON, Chart.js or external API/library is used.</div>

        <div class="kpi-grid">
            <div class="kpi-card"><div class="kpi-label">Total Donations</div><div class="kpi-value"><?php echo $totalDonations; ?></div></div>
            <div class="kpi-card"><div class="kpi-label">Food Items</div><div class="kpi-value"><?php echo $totalFoodItems; ?></div></div>
            <div class="kpi-card"><div class="kpi-label">Food Quantity Rescued</div><div class="kpi-value"><?php echo $totalFoodQuantity; ?></div></div>
            <div class="kpi-card"><div class="kpi-label">Completed Distributions</div><div class="kpi-value"><?php echo $completedDistributions; ?></div></div>
        </div>

        <div class="chart-grid">
            <div class="chart-card large-chart">
                <h3>Distribution Status</h3>
                <div class="pie-layout">
                    <div class="pie-chart"><div class="pie-hole"></div></div>
                    <ul class="legend">
                        <?php foreach ($distributionStatusRows as $index => $row) { ?>
                            <?php $legendColor = $pieColors[$index % count($pieColors)]; ?>
                            <li><span class="legend-dot" style="background:<?php echo $legendColor; ?>"></span><?php echo htmlspecialchars($row["status"]); ?>: <?php echo (int)$row["total_distributions"]; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="chart-card">
                <h3>Food Quantity by Donor</h3>
                <?php if (count($donorImpactRows) > 0) { ?>
                    <?php foreach ($donorImpactRows as $row) { ?>
                        <?php $barWidth = $donorImpactMax > 0 ? ((int)$row["total_food_quantity"] / $donorImpactMax) * 100 : 0; ?>
                        <div class="bar-row">
                            <div class="bar-label"><span><?php echo htmlspecialchars($row["name"]); ?></span><strong><?php echo (int)$row["total_food_quantity"]; ?></strong></div>
                            <div class="bar-track"><div class="bar-fill" style="width:<?php echo $barWidth; ?>%"></div></div>
                        </div>
                    <?php } ?>
                <?php } else { ?><p>No donor impact data found.</p><?php } ?>
            </div>

            <div class="chart-card">
                <h3>Mission Impact</h3>
                <?php if (count($missionImpactRows) > 0) { ?>
                    <?php foreach ($missionImpactRows as $row) { ?>
                        <?php $barWidth = $missionImpactMax > 0 ? ((int)$row["total_allocated_quantity"] / $missionImpactMax) * 100 : 0; ?>
                        <div class="bar-row">
                            <div class="bar-label"><span><?php echo htmlspecialchars($row["mission_name"]); ?></span><strong><?php echo (int)$row["total_allocated_quantity"]; ?></strong></div>
                            <div class="bar-track"><div class="bar-fill" style="width:<?php echo $barWidth; ?>%"></div></div>
                        </div>
                    <?php } ?>
                <?php } else { ?><p>No mission impact data found.</p><?php } ?>
            </div>

            <div class="chart-card large-chart">
                <h3>Food Rescue Trend</h3>
                <div class="line-chart">
                    <?php
                    $pointCount = count($impactTrendRows);
                    $svgWidth = 900; $svgHeight = 340; $left = 55; $right = 25; $top = 25; $bottom = 55;
                    $plotWidth = $svgWidth - $left - $right; $plotHeight = $svgHeight - $top - $bottom;
                    $points = array();
                    foreach ($impactTrendRows as $index => $row) {
                        $x = $pointCount == 1 ? $left + ($plotWidth / 2) : $left + ($index * $plotWidth / ($pointCount - 1));
                        $value = (int)$row["total_food_quantity"];
                        $y = $top + $plotHeight - ($trendMax > 0 ? ($value / $trendMax) * $plotHeight : 0);
                        $points[] = round($x,2) . "," . round($y,2);
                    }
                    ?>
                    <svg viewBox="0 0 900 340" preserveAspectRatio="none">
                        <line x1="55" y1="285" x2="875" y2="285" class="chart-grid-line" />
                        <line x1="55" y1="155" x2="875" y2="155" class="chart-grid-line" />
                        <line x1="55" y1="25" x2="875" y2="25" class="chart-grid-line" />
                        <?php if (count($points) > 0) { ?>
                            <polyline points="<?php echo implode(" ", $points); ?>" class="chart-line" />
                            <?php foreach ($impactTrendRows as $index => $row) { ?>
                                <?php
                                $x = $pointCount == 1 ? $left + ($plotWidth / 2) : $left + ($index * $plotWidth / ($pointCount - 1));
                                $value = (int)$row["total_food_quantity"];
                                $y = $top + $plotHeight - ($trendMax > 0 ? ($value / $trendMax) * $plotHeight : 0);
                                ?>
                                <circle cx="<?php echo round($x,2); ?>" cy="<?php echo round($y,2); ?>" r="5" class="chart-point" />
                                <text x="<?php echo round($x,2); ?>" y="<?php echo round($y-10,2); ?>" text-anchor="middle" class="chart-axis-label"><?php echo $value; ?></text>
                                <text x="<?php echo round($x,2); ?>" y="318" text-anchor="middle" class="chart-axis-label"><?php echo htmlspecialchars($row["donation_date"]); ?></text>
                            <?php } ?>
                        <?php } else { ?><text x="450" y="170" text-anchor="middle" class="chart-axis-label">No trend data found</text><?php } ?>
                    </svg>
                </div>
            </div>
        </div>

        <h2 class="dashboard-heading">Mission Impact Analysis</h2>
        <p class="dashboard-subtitle">This follows the database relationship from rescue missions to distributions and resource allocations.</p>
        <table class="analytics-table">
            <tr><th>Mission</th><th>Mission Date</th><th>Status</th><th>Distributions</th><th>Allocations</th><th>Total Allocated Quantity</th></tr>
            <?php if (count($missionImpactRows) > 0) { ?>
                <?php foreach ($missionImpactRows as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["mission_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["mission_date"]); ?></td>
                        <td><span class="status-badge"><?php echo htmlspecialchars($row["status"]); ?></span></td>
                        <td><?php echo (int)$row["total_distributions"]; ?></td>
                        <td><?php echo (int)$row["total_allocations"]; ?></td>
                        <td><?php echo (int)$row["total_allocated_quantity"]; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?><tr><td colspan="6">No mission impact data found.</td></tr><?php } ?>
        </table>

        <br><br>
        <h2 class="dashboard-heading">Donor Contribution Analysis</h2>
        <table class="analytics-table">
            <tr><th>Donor</th><th>Total Donations</th><th>Food Items</th><th>Total Food Quantity</th></tr>
            <?php if (count($donorImpactRows) > 0) { ?>
                <?php foreach ($donorImpactRows as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo (int)$row["total_donations"]; ?></td>
                        <td><?php echo (int)$row["total_food_items"]; ?></td>
                        <td><?php echo (int)$row["total_food_quantity"]; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?><tr><td colspan="4">No donor impact data found.</td></tr><?php } ?>
        </table>
    </section>
</div>

<?php $conn->close(); ?>
</body>
</html>
