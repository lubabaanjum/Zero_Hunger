<?php

require_once "DBconnect.php";

/* =========================================================
   EXISTING MISSION QUERY - KEPT FOR THE ORIGINAL TABLE
   ========================================================= */
$sql = "SELECT 
            m.mission_id,
            m.mission_name,
            m.mission_date,
            m.status,
            m.partner_id,
            p.organization_name
        FROM Rescue_Mission m
        JOIN Partner_Organization p 
        ON m.partner_id = p.partner_id";

$result = $conn->query($sql);

/* Store the existing table rows so the same result can also be
   used by the new visual section without changing the old table. */
$missionRows = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $missionRows[] = $row;
    }
}

/* =========================================================
   NEW DASHBOARD QUERIES - ADDED BELOW THE ORIGINAL FEATURE
   ========================================================= */

$missionStatusSql = "SELECT status, COUNT(*) AS total_missions
                     FROM Rescue_Mission
                     GROUP BY status
                     ORDER BY total_missions DESC";
$missionStatusResult = $conn->query($missionStatusSql);
$missionStatusLabels = array();
$missionStatusValues = array();

if ($missionStatusResult) {
    while ($row = $missionStatusResult->fetch_assoc()) {
        $missionStatusLabels[] = $row["status"];
        $missionStatusValues[] = (int)$row["total_missions"];
    }
}

/* Complex mission-impact query:
   Rescue_Mission -> Distribution -> Resource_Allocation -> quantity */
$missionImpactSql = "SELECT
                        m.mission_id,
                        m.mission_name,
                        m.mission_date,
                        m.status,
                        p.organization_name,
                        COUNT(DISTINCT d.distribution_id) AS total_distributions,
                        COUNT(DISTINCT a.allocation_id) AS total_allocations,
                        COALESCE(SUM(a.quantity), 0) AS total_allocated_quantity
                    FROM Rescue_Mission m
                    JOIN Partner_Organization p
                        ON m.partner_id = p.partner_id
                    LEFT JOIN Distribution d
                        ON m.mission_id = d.mission_id
                    LEFT JOIN Resource_Allocation a
                        ON d.allocation_id = a.allocation_id
                    GROUP BY
                        m.mission_id,
                        m.mission_name,
                        m.mission_date,
                        m.status,
                        p.organization_name
                    HAVING COUNT(DISTINCT d.distribution_id) > 0
                    ORDER BY total_allocated_quantity DESC";
$missionImpactResult = $conn->query($missionImpactSql);
$missionImpactRows = array();
$missionImpactLabels = array();
$missionImpactValues = array();

if ($missionImpactResult) {
    while ($row = $missionImpactResult->fetch_assoc()) {
        $missionImpactRows[] = $row;
        $missionImpactLabels[] = $row["mission_name"];
        $missionImpactValues[] = (int)$row["total_allocated_quantity"];
    }
}

$missionTimelineSql = "SELECT mission_date, COUNT(*) AS total_missions
                       FROM Rescue_Mission
                       GROUP BY mission_date
                       ORDER BY mission_date ASC";
$missionTimelineResult = $conn->query($missionTimelineSql);
$missionTimelineLabels = array();
$missionTimelineValues = array();

if ($missionTimelineResult) {
    while ($row = $missionTimelineResult->fetch_assoc()) {
        $missionTimelineLabels[] = $row["mission_date"];
        $missionTimelineValues[] = (int)$row["total_missions"];
    }
}

$missionKpiSql = "SELECT
                    COUNT(*) AS total_missions,
                    SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_missions,
                    SUM(CASE WHEN status = 'Ongoing' THEN 1 ELSE 0 END) AS ongoing_missions,
                    SUM(CASE WHEN status = 'Planned' THEN 1 ELSE 0 END) AS planned_missions
                  FROM Rescue_Mission";
$missionKpiResult = $conn->query($missionKpiSql);
$missionKpi = array(
    "total_missions" => 0,
    "completed_missions" => 0,
    "ongoing_missions" => 0,
    "planned_missions" => 0
);

if ($missionKpiResult && $missionKpiResult->num_rows > 0) {
    $missionKpi = $missionKpiResult->fetch_assoc();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Rescue Missions</title>

    <link rel="stylesheet" href="css/style.css">

    <!-- Chart.js is used only for the new visual section. -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* =====================================================
           NEW VISUAL SECTION - DOES NOT CHANGE THE OLD TABLE
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

    <div class="logo">
        ZERO HUNGER
    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>
        <a href="showMission.php">Rescue Missions</a>
        <a href="showAllocations.php">Resource Allocation</a>
        <a href="showImpact.php">Community Impact</a>
        <a href="showPartners.php">Partners</a>

    </div>

</nav>

<div class="container">

    <!-- =====================================================
         EXISTING PAGE - KEPT
         ===================================================== -->

    <h1 class="page-title">
        Community Rescue Mission Coordination
    </h1>

    <p>
        Coordinate food rescue missions, partner organizations
        and monitor mission progress.
    </p>

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
                        <a href="deleteMission.php?mission_id=<?php echo $row['mission_id']; ?>"
                           class="btn"
                           onclick="return confirm('Are you sure you want to delete this rescue mission?');">Delete</a>
                    </td>
                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>
                <td colspan="7">No rescue missions found.</td>
            </tr>

        <?php } ?>

    </table>


    <!-- =====================================================
         NEW SECOND SECTION / PAGE-LIKE DASHBOARD
         ===================================================== -->

    <section class="dashboard-section">

        <h2 class="dashboard-heading">Mission Progress Dashboard</h2>

        <p class="dashboard-subtitle">
            A visual overview of rescue mission activity, mission status and
            the amount of food connected to completed or active distributions.
        </p>

        <div class="section-note">
            The original mission table above is unchanged. This section is an
            additional analytical view for the Community Rescue Mission feature.
        </div>

        <div class="kpi-grid">

            <div class="kpi-card">
                <div class="kpi-label">Total Missions</div>
                <div class="kpi-value"><?php echo (int)$missionKpi["total_missions"]; ?></div>
            </div>

            <div class="kpi-card">
                <div class="kpi-label">Completed Missions</div>
                <div class="kpi-value"><?php echo (int)$missionKpi["completed_missions"]; ?></div>
            </div>

            <div class="kpi-card">
                <div class="kpi-label">Ongoing Missions</div>
                <div class="kpi-value"><?php echo (int)$missionKpi["ongoing_missions"]; ?></div>
            </div>

            <div class="kpi-card">
                <div class="kpi-label">Planned Missions</div>
                <div class="kpi-value"><?php echo (int)$missionKpi["planned_missions"]; ?></div>
            </div>

        </div>

        <div class="chart-grid">

            <div class="chart-card">
                <h3>Mission Status Distribution</h3>
                <div class="chart-box">
                    <canvas id="missionStatusChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>Food Allocated Through Missions</h3>
                <div class="chart-box">
                    <canvas id="missionImpactChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>Mission Timeline</h3>
                <div class="chart-box">
                    <canvas id="missionTimelineChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>What This Shows</h3>
                <p>
                    The mission impact chart connects a rescue mission to its
                    distributions and resource allocations. A mission with no
                    distribution is not included in that impact chart, which
                    makes the visualization useful for monitoring actual mission activity.
                </p>
                <br>
                <p>
                    The timeline shows how many rescue missions are scheduled
                    on each mission date.
                </p>
            </div>

        </div>

        <h2 class="dashboard-heading">Mission Distribution Analysis</h2>

        <p class="dashboard-subtitle">
            This table is an additional analytical result produced from the
            mission → distribution → allocation relationship.
        </p>

        <table class="analytics-table">
            <tr>
                <th>Mission</th>
                <th>Partner</th>
                <th>Status</th>
                <th>Distributions</th>
                <th>Allocations</th>
                <th>Total Allocated Quantity</th>
            </tr>

            <?php if (count($missionImpactRows) > 0) { ?>
                <?php foreach ($missionImpactRows as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["mission_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["organization_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["status"]); ?></td>
                        <td><?php echo (int)$row["total_distributions"]; ?></td>
                        <td><?php echo (int)$row["total_allocations"]; ?></td>
                        <td><?php echo (int)$row["total_allocated_quantity"]; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6">No mission distribution analysis data found.</td>
                </tr>
            <?php } ?>
        </table>

    </section>

</div>

<script>
const missionStatusLabels = <?php echo json_encode($missionStatusLabels); ?>;
const missionStatusValues = <?php echo json_encode($missionStatusValues); ?>;
const missionImpactLabels = <?php echo json_encode($missionImpactLabels); ?>;
const missionImpactValues = <?php echo json_encode($missionImpactValues); ?>;
const missionTimelineLabels = <?php echo json_encode($missionTimelineLabels); ?>;
const missionTimelineValues = <?php echo json_encode($missionTimelineValues); ?>;

new Chart(document.getElementById('missionStatusChart'), {
    type: 'pie',
    data: {
        labels: missionStatusLabels,
        datasets: [{
            data: missionStatusValues
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('missionImpactChart'), {
    type: 'bar',
    data: {
        labels: missionImpactLabels,
        datasets: [{
            label: 'Allocated Quantity',
            data: missionImpactValues
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

new Chart(document.getElementById('missionTimelineChart'), {
    type: 'line',
    data: {
        labels: missionTimelineLabels,
        datasets: [{
            label: 'Missions',
            data: missionTimelineValues,
            tension: 0.25,
            fill: false
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
