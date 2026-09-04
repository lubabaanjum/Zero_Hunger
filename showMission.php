<?php
require_once "DBconnect.php";

$sql = "SELECT m.mission_id,m.mission_name,m.mission_date,m.status,m.partner_id,p.organization_name
FROM Rescue_Mission m JOIN Partner_Organization p ON m.partner_id = p.partner_id";

$result = $conn->query($sql);

$missionRows = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $missionRows[] = $row;
    }
}


$totalMissionResult = $conn->query("SELECT COUNT(*) AS total FROM Rescue_Mission");
$totalMissions = 0;
if ($totalMissionResult && $totalMissionResult->num_rows > 0) {
    $totalMissions = (int)$totalMissionResult->fetch_assoc()["total"];
}

$completedResult = $conn->query("SELECT COUNT(*) AS total FROM Rescue_Mission WHERE status = 'Completed'");
$completedMissions = 0;
if ($completedResult && $completedResult->num_rows > 0) {
    $completedMissions = (int)$completedResult->fetch_assoc()["total"];
}

$ongoingResult = $conn->query("SELECT COUNT(*) AS total FROM Rescue_Mission WHERE status = 'Ongoing'");
$ongoingMissions = 0;
if ($ongoingResult && $ongoingResult->num_rows > 0) {
    $ongoingMissions = (int)$ongoingResult->fetch_assoc()["total"];
}

$plannedResult = $conn->query("SELECT COUNT(*) AS total FROM Rescue_Mission WHERE status = 'Planned'");
$plannedMissions = 0;
if ($plannedResult && $plannedResult->num_rows > 0) {
    $plannedMissions = (int)$plannedResult->fetch_assoc()["total"];
}

$missionStatusResult = $conn->query("SELECT status, COUNT(*) AS total_missions
                                     FROM Rescue_Mission
                                     GROUP BY status
                                     ORDER BY total_missions DESC");
$missionStatusRows = array();
$missionStatusTotal = 0;
if ($missionStatusResult) {
    while ($row = $missionStatusResult->fetch_assoc()) {
        $missionStatusRows[] = $row;
        $missionStatusTotal += (int)$row["total_missions"];
    }
}

$missionImpactSql = "SELECT m.mission_id,m.mission_name,m.mission_date,m.status,p.organization_name,COUNT(d.distribution_id) AS total_distributions,
COUNT(a.allocation_id) AS total_allocations, SUM(a.quantity) AS total_allocated_quantity FROM Rescue_Mission m JOIN Partner_Organization p
ON m.partner_id = p.partner_id JOIN Distribution d ON m.mission_id = d.mission_id JOIN Resource_Allocation a ON d.allocation_id = a.allocation_id
GROUP BY m.mission_id,m.mission_name,m.mission_date,m.status,p.organization_name ORDER BY total_allocated_quantity DESC";
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

$missionTimelineResult = $conn->query("SELECT mission_id, mission_name, mission_date
                                       FROM Rescue_Mission
                                       ORDER BY mission_date ASC");
$missionTimelineRows = array();
if ($missionTimelineResult) {
    while ($row = $missionTimelineResult->fetch_assoc()) {
        $missionTimelineRows[] = $row;
    }
}

$pieParts = array();
$pieStart = 0;
$pieColors = array("#2f8f5b", "#f0a64b", "#5b7cfa", "#b66dff", "#e56b6f");
foreach ($missionStatusRows as $index => $row) {
    $value = (int)$row["total_missions"];
    $percentage = $missionStatusTotal > 0 ? ($value / $missionStatusTotal) * 100 : 0;
    $pieEnd = $pieStart + $percentage;
    $color = $pieColors[$index % count($pieColors)];
    $pieParts[] = $color . " " . $pieStart . "% " . $pieEnd . "%";
    $pieStart = $pieEnd;
}
$missionPieGradient = count($pieParts) > 0 ? implode(", ", $pieParts) : "#e8ece9 0% 100%";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rescue Missions</title>
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
        .bar-chart { width:100%; }
        .bar-row { margin:14px 0; }
        .bar-label { display:flex; justify-content:space-between; gap:12px; margin-bottom:6px; font-size:14px; }
        .bar-track { height:24px; background:#edf2ee; border-radius:12px; overflow:hidden; }
        .bar-fill { height:100%; background:#2f8f5b; border-radius:12px; min-width:2px; }
        .pie-layout { display:flex; align-items:center; justify-content:center; gap:25px; flex-wrap:wrap; }
        .pie-chart { width:220px; height:220px; border-radius:50%; background:conic-gradient(<?php echo $missionPieGradient; ?>); }
        .pie-hole { width:90px; height:90px; background:white; border-radius:50%; position:relative; top:65px; left:65px; }
        .legend { margin:0; padding:0; list-style:none; }
        .legend li { margin:9px 0; }
        .legend-dot { display:inline-block; width:12px; height:12px; border-radius:50%; margin-right:7px; }
        .timeline-card { grid-column:1 / -1; min-height:500px; }
        .timeline-card .line-chart { width:100%; height:400px; border:1px solid #e1e8e2; background:#fafcfb; border-radius:8px; }
        .what-shows-card { grid-column:2; min-height:220px; }
        .line-chart svg { width:100%; height:100%; }
        .chart-axis-label { fill:#68736b; font-size:11px; }
        .chart-grid-line { stroke:#dfe7e1; stroke-width:1; }
        .chart-line { fill:none; stroke:#2f8f5b; stroke-width:3; }
        .chart-point { fill:#2f8f5b; }
        .analytics-table { margin-top:25px; }
        .status-badge { display:inline-block; padding:4px 9px; border-radius:12px; background:#eef8f1; color:#246b45; font-size:12px; font-weight:bold; }
        @media(max-width:850px){ .kpi-grid,.chart-grid{grid-template-columns:1fr 1fr;} }
        @media(max-width:550px){ .kpi-grid,.chart-grid{grid-template-columns:1fr;} .pie-chart{width:180px;height:180px;} .pie-hole{top:50px;left:50px;} }
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
    <h1 class="page-title">Community Rescue Mission Coordination</h1>
    <p>Coordinate food rescue missions, partner organizations and monitor mission progress.</p>

    <div class="feature-buttons">
        <a href="addMission.php" class="btn">Add Rescue Mission</a>
        <a href="showMission.php" class="btn">View Missions</a>
        <a href="showPartners.php" class="btn">Partner Organizations</a>
        <a href="showDistributions.php" class="btn">Distribution Records</a>
        <a href="showCompleted.php" class="btn">Completed Missions</a>
        <a href="searchMissions.php" class="btn">Search Missions</a>
    </div>

    <br><br>

    <table>
        <tr>
            <th>Mission ID</th>
            <th>Mission Name</th>
            <th>Mission Date</th>
            <th>Status</th>
            <th>Partner ID</th>
            <th>Partner Organization</th>
            <th>Actions</th>
        </tr>
        <?php if (count($missionRows) > 0) { ?>
            <?php foreach ($missionRows as $row) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["mission_id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["mission_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["mission_date"]); ?></td>
                    <td><?php echo htmlspecialchars($row["status"]); ?></td>
                    <td><?php echo htmlspecialchars($row["partner_id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["organization_name"]); ?></td>
                    <td>
                        <a href="editMission.php?mission_id=<?php echo $row['mission_id']; ?>" class="btn">Edit</a>
                        <a href="deleteMission.php?mission_id=<?php echo $row['mission_id']; ?>" class="btn" onclick="return confirm('Are you sure you want to delete this rescue mission?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="7">No rescue missions found.</td></tr>
        <?php } ?>
    </table>

    <section class="dashboard-section">
        <h2 class="dashboard-heading">Mission Progress Dashboard</h2>
        <p class="dashboard-subtitle">A visual overview of rescue mission activity, status and food allocation.</p>
        <div class="section-note">This is an additional analytical section below the original mission page. All values are loaded directly by PHP from MySQL using raw SQL. No AJAX, JSON, chart library or API is used.</div>

        <div class="kpi-grid">
            <div class="kpi-card"><div class="kpi-label">Total Missions</div><div class="kpi-value"><?php echo $totalMissions; ?></div></div>
            <div class="kpi-card"><div class="kpi-label">Completed Missions</div><div class="kpi-value"><?php echo $completedMissions; ?></div></div>
            <div class="kpi-card"><div class="kpi-label">Ongoing Missions</div><div class="kpi-value"><?php echo $ongoingMissions; ?></div></div>
            <div class="kpi-card"><div class="kpi-label">Planned Missions</div><div class="kpi-value"><?php echo $plannedMissions; ?></div></div>
        </div>

        <div class="chart-grid">
            <div class="chart-card">
                <h3>Mission Status Distribution</h3>
                <div class="pie-layout">
                    <div class="pie-chart"><div class="pie-hole"></div></div>
                    <ul class="legend">
                        <?php foreach ($missionStatusRows as $index => $row) { ?>
                            <?php $legendColor = $pieColors[$index % count($pieColors)]; ?>
                            <li><span class="legend-dot" style="background:<?php echo $legendColor; ?>"></span><?php echo htmlspecialchars($row["status"]); ?>: <?php echo (int)$row["total_missions"]; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="chart-card">
                <h3>Food Allocated Through Missions</h3>
                <div class="bar-chart">
                    <?php if (count($missionImpactRows) > 0) { ?>
                        <?php foreach ($missionImpactRows as $row) { ?>
                            <?php $barWidth = $missionImpactMax > 0 ? ((int)$row["total_allocated_quantity"] / $missionImpactMax) * 100 : 0; ?>
                            <div class="bar-row">
                                <div class="bar-label"><span><?php echo htmlspecialchars($row["mission_name"]); ?></span><strong><?php echo (int)$row["total_allocated_quantity"]; ?></strong></div>
                                <div class="bar-track"><div class="bar-fill" style="width:<?php echo $barWidth; ?>%"></div></div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p>No mission allocation data found.</p>
                    <?php } ?>
                </div>
            </div>

            <div class="chart-card timeline-card">
                <h3>Mission Timeline</h3>
                <div class="line-chart">
                    <?php
                    $pointCount = count($missionTimelineRows);
                    $svgWidth = 1200;
                    $svgHeight = 400;
                    $left = 85;
                    $right = 45;
                    $timelineY = 185;
                    $plotWidth = $svgWidth - $left - $right;
                    $points = array();
                    if ($pointCount > 0) {
                        foreach ($missionTimelineRows as $index => $row) {
                            $x = $pointCount == 1 ? $left + ($plotWidth / 2) : $left + ($index * $plotWidth / ($pointCount - 1));
                            $points[] = round($x, 2) . "," . $timelineY;
                        }
                    }
                    ?>
                    <svg viewBox="0 0 <?php echo $svgWidth; ?> <?php echo $svgHeight; ?>" preserveAspectRatio="none">
                        <line x1="<?php echo $left; ?>" y1="<?php echo $timelineY; ?>" x2="<?php echo $svgWidth - $right; ?>" y2="<?php echo $timelineY; ?>" class="chart-grid-line" style="stroke-width:3;" />
                        <?php if (count($points) > 0) { ?>
                            <polyline points="<?php echo implode(" ", $points); ?>" class="chart-line" />
                            <?php foreach ($missionTimelineRows as $index => $row) { ?>
                                <?php
                                $x = $pointCount == 1 ? $left + ($plotWidth / 2) : $left + ($index * $plotWidth / ($pointCount - 1));
                                ?>
                                <circle cx="<?php echo round($x,2); ?>" cy="<?php echo $timelineY; ?>" r="7" class="chart-point" />
                                <text x="<?php echo round($x,2); ?>" y="95" text-anchor="middle" class="chart-axis-label" style="font-weight:bold;font-size:18px;">Mission <?php echo htmlspecialchars($row["mission_id"]); ?></text>
                                <text x="<?php echo round($x,2); ?>" y="112" text-anchor="middle" class="chart-axis-label" style="font-size:15px;"><?php echo htmlspecialchars($row["mission_name"]); ?></text>
                                <text x="<?php echo round($x,2); ?>" y="225" text-anchor="middle" class="chart-axis-label" style="font-size:15px;"><?php echo htmlspecialchars($row["mission_date"]); ?></text>
                            <?php } ?>
                        <?php } else { ?>
                            <text x="600" y="205" text-anchor="middle" class="chart-axis-label">No timeline data found</text>
                        <?php } ?>
                    </svg>
                </div>
            </div>

            <div class="chart-card what-shows-card">
                <h3>What This Shows</h3>
                <p>The status pie shows the proportion of missions by their current status.</p>
                <br>
                <p>The allocation chart follows the database relationship from a mission to its distribution and resource allocation.</p>
                <br>
                <p>The timeline shows each rescue mission with its mission ID, mission name and scheduled date.</p>
            </div>
        </div>

        <h2 class="dashboard-heading">Mission Distribution Analysis</h2>
        <p class="dashboard-subtitle">This table is based on the mission → distribution → resource allocation relationship.</p>
        <table class="analytics-table">
            <tr><th>Mission</th><th>Partner</th><th>Status</th><th>Distributions</th><th>Allocations</th><th>Total Allocated Quantity</th></tr>
            <?php if (count($missionImpactRows) > 0) { ?>
                <?php foreach ($missionImpactRows as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["mission_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["organization_name"]); ?></td>
                        <td><span class="status-badge"><?php echo htmlspecialchars($row["status"]); ?></span></td>
                        <td><?php echo (int)$row["total_distributions"]; ?></td>
                        <td><?php echo (int)$row["total_allocations"]; ?></td>
                        <td><?php echo (int)$row["total_allocated_quantity"]; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr><td colspan="6">No mission distribution analysis data found.</td></tr>
            <?php } ?>
        </table>
    </section>
</div>

<?php $conn->close(); ?>
</body>
</html>
