<?php

require_once "DBconnect.php";

$sql = "SELECT p.partner_id, p.organization_name, p.location, COUNT(m.mission_id) AS total_missions, MIN(m.mission_date) AS first_mission,
MAX(m.mission_date) AS latest_mission FROM Partner_Organization p LEFT JOIN Rescue_Mission m ON p.partner_id = m.partner_id
WHERE p.organization_name IS NOT NULL AND p.organization_name <> '' GROUP BY p.partner_id, p.organization_name, p.location
ORDER BY total_missions DESC";

$result = $conn->query($sql);

$missionRows = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $missionRows[] = $row;
    }
}

$totalPartners = count($missionRows);
$totalMissions = 0;
$partnersWithMissions = 0;

foreach ($missionRows as $row) {
    $totalMissions += (int)$row["total_missions"];
    if ((int)$row["total_missions"] > 0) {
        $partnersWithMissions++;
    }
}

$partnersWithoutMissions = $totalPartners - $partnersWithMissions;

$coverageSlices = array();
if ($totalPartners > 0) {
    $withPercent = ($partnersWithMissions / $totalPartners) * 100;
    $withoutPercent = 100 - $withPercent;

    $coverageSlices[] = array(
        "label" => "Partners With Missions",
        "value" => $partnersWithMissions,
        "start" => 0,
        "end" => $withPercent,
        "color" => "#1f6f43"
    );

    $coverageSlices[] = array(
        "label" => "Partners With No Missions Yet",
        "value" => $partnersWithoutMissions,
        "start" => $withPercent,
        "end" => 100,
        "color" => "#dcece2"
    );
}

$partnerBarLabels = array();
$partnerBarValues = array();
foreach ($missionRows as $row) {
    $partnerBarLabels[] = $row["organization_name"];
    $partnerBarValues[] = (int)$row["total_missions"];
}
$partnerBarMax = count($partnerBarValues) > 0 ? max($partnerBarValues) : 0;

?>

<!DOCTYPE html>
<html>

<head>

    <title>Mission Impact Analysis</title>

    <link rel="stylesheet" href="css/style.css">

    <style>
        .dashboard-section {
            margin-top: 40px;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin: 25px 0 35px;
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
            margin-bottom: 35px;
        }

        .chart-card {
            background: white;
            border: 1px solid #e4ebe5;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);
            min-height: 320px;
        }

        .chart-card h3 {
            margin-bottom: 15px;
            font-family: Georgia, "Times New Roman", serif;
            color: #1f6f43;
        }

        .pie-wrap {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .pie-chart {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pie-legend {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pie-legend li {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 14px;
            color: #3a463d;
        }

        .legend-swatch {
            width: 13px;
            height: 13px;
            border-radius: 3px;
            display: inline-block;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            height: 220px;
            padding-top: 10px;
            border-bottom: 2px solid #dfe9e2;
            overflow-x: auto;
        }

        .bar-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            height: 100%;
            flex: 1;
            min-width: 60px;
        }

        .bar-fill {
            width: 65%;
            background: linear-gradient(180deg, #2f8f5b, #1f6f43);
            border-radius: 6px 6px 0 0;
            min-height: 2px;
        }

        .bar-value {
            font-size: 12px;
            color: #1f6f43;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .bar-label {
            font-size: 11px;
            color: #68736b;
            margin-top: 6px;
            text-align: center;
            word-break: break-word;
        }

        @media (max-width: 850px) {
            .kpi-grid, .chart-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 550px) {
            .kpi-grid, .chart-grid {
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

        <a href="index.php">
            Home
        </a>

        <a href="showMission.php">
            Rescue Missions
        </a>

        <a href="showAllocations.php">
            Allocations
        </a>

        <a href="showPartners.php">
            Partners
        </a>

    </div>

</nav>


<div class="container">

    <h1 class="page-title">
        Rescue Mission Impact Analysis
    </h1>

    <p>
        This report shows the rescue mission activity
        handled by each partner organization, including
        partner organizations that have not yet taken
        part in any rescue mission.
    </p>

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-label">Total Partner Organizations</div>
            <div class="kpi-value"><?php echo $totalPartners; ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Total Missions Recorded</div>
            <div class="kpi-value"><?php echo $totalMissions; ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Partners With Missions</div>
            <div class="kpi-value"><?php echo $partnersWithMissions; ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Partners With No Missions Yet</div>
            <div class="kpi-value"><?php echo $partnersWithoutMissions; ?></div>
        </div>

    </div>

    <div class="chart-grid">

        <div class="chart-card">
            <h3>Partner Mission Coverage</h3>

            <?php if (count($coverageSlices) > 0) { ?>
                <div class="pie-wrap">

                    <div class="pie-chart" style="background: conic-gradient(
                        <?php
                        $sliceParts = array();
                        foreach ($coverageSlices as $slice) {
                            $sliceParts[] = $slice["color"] . " " . round($slice["start"], 2) . "% " . round($slice["end"], 2) . "%";
                        }
                        echo implode(", ", $sliceParts);
                        ?>
                    );"></div>

                    <ul class="pie-legend">
                        <?php foreach ($coverageSlices as $slice) { ?>
                            <li>
                                <span class="legend-swatch" style="background: <?php echo $slice["color"]; ?>;"></span>
                                <?php echo htmlspecialchars($slice["label"]); ?>
                                (<?php echo (int)$slice["value"]; ?>)
                            </li>
                        <?php } ?>
                    </ul>

                </div>
            <?php } else { ?>
                <p>No partner organization data found.</p>
            <?php } ?>
        </div>

        <div class="chart-card">
            <h3>Total Missions by Partner</h3>

            <?php if (count($partnerBarLabels) > 0) { ?>
                <div class="bar-chart">
                    <?php foreach ($partnerBarLabels as $index => $label) {
                        $value = $partnerBarValues[$index];
                        $heightPercent = $partnerBarMax > 0 ? ($value / $partnerBarMax) * 100 : 0;
                    ?>
                        <div class="bar-column">
                            <div class="bar-value"><?php echo $value; ?></div>
                            <div class="bar-fill" style="height: <?php echo round($heightPercent, 2); ?>%;"></div>
                            <div class="bar-label"><?php echo htmlspecialchars($label); ?></div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>No mission data found.</p>
            <?php } ?>
        </div>

    </div>

    <table>

        <tr>

            <th>Partner ID</th>

            <th>Partner Organization</th>

            <th>Location</th>

            <th>Total Missions</th>

            <th>First Mission</th>

            <th>Latest Mission</th>

        </tr>


        <?php if (count($missionRows) > 0) { ?>

            <?php foreach ($missionRows as $row) { ?>

        <tr>

            <td>
                <?php
                echo $row["partner_id"];
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row["organization_name"]);
                ?>
            </td>


            <td>
                <?php

                if (
                    $row["location"] !== null &&
                    $row["location"] !== ""
                ) {

                    echo htmlspecialchars(
                        $row["location"]
                    );

                } else {

                    echo "Not Provided";

                }

                ?>
            </td>


            <td>
                <?php
                echo $row["total_missions"];
                ?>
            </td>


            <td>
                <?php

                if ($row["first_mission"] !== null) {
                    echo $row["first_mission"];
                } else {
                    echo "No missions yet";
                }

                ?>
            </td>


            <td>
                <?php

                if ($row["latest_mission"] !== null) {
                    echo $row["latest_mission"];
                } else {
                    echo "No missions yet";
                }

                ?>
            </td>

        </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>

                <td colspan="6">
                    No partner organization data found.
                </td>

            </tr>

        <?php } ?>

    </table>


</div>


</body>

</html>


<?php

$conn->close();

?>
