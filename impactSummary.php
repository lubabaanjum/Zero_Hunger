<?php

require_once "DBconnect.php";

$sql = "SELECT donation_date, COUNT(donation_id) AS total_donations  FROM Food_donation GROUP BY donation_date ORDER BY donation_date ASC";

$result = $conn->query($sql);

$donationTrendRows = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $donationTrendRows[] = $row;
    }
}

$totalDonationsSql = "SELECT COUNT(*) AS total_donations FROM Food_donation";
$totalDonationsResult = $conn->query($totalDonationsSql);
$totalDonations = 0;
if ($totalDonationsResult && $totalDonationsResult->num_rows > 0) {
    $totalDonations = (int)$totalDonationsResult->fetch_assoc()["total_donations"];
}

$foodTotalsSql = "SELECT COUNT(*) AS total_items, SUM(quantity) AS total_quantity FROM Food_item";
$foodTotalsResult = $conn->query($foodTotalsSql);
$totalFoodItems = 0;
$totalFoodQuantity = 0;
if ($foodTotalsResult && $foodTotalsResult->num_rows > 0) {
    $foodTotalsRow = $foodTotalsResult->fetch_assoc();
    $totalFoodItems = (int)$foodTotalsRow["total_items"];
    $totalFoodQuantity = (int)$foodTotalsRow["total_quantity"];
}

$donorGroupSql = "SELECT donor_id, COUNT(*) AS total_donations
                  FROM Food_donation
                  GROUP BY donor_id";
$donorGroupResult = $conn->query($donorGroupSql);
$totalDonors = 0;
if ($donorGroupResult) {
    $totalDonors = $donorGroupResult->num_rows;
}


$statusSql = "SELECT status, COUNT(*) AS total FROM Food_donation GROUP BY status ORDER BY total DESC";
$statusResult = $conn->query($statusSql);
$statusLabels = array();
$statusValues = array();
if ($statusResult) {
    while ($row = $statusResult->fetch_assoc()) {
        $statusLabels[] = $row["status"];
        $statusValues[] = (int)$row["total"];
    }
}

$quantityTrendSql = "SELECT fd.donation_date, SUM(fi.quantity) AS total_quantity FROM Food_donation fd LEFT JOIN Food_item fi
ON fd.donation_id = fi.donation_id GROUP BY fd.donation_date ORDER BY fd.donation_date ASC";
$quantityTrendResult = $conn->query($quantityTrendSql);
$quantityTrendLabels = array();
$quantityTrendValues = array();
if ($quantityTrendResult) {
    while ($row = $quantityTrendResult->fetch_assoc()) {
        $quantityTrendLabels[] = $row["donation_date"];
        $quantityTrendValues[] = (int)$row["total_quantity"];
    }
}

/* =========================================================
   PHP-ONLY CHART PREP (no library, no AJAX)
   ========================================================= */

/* --- Pie: donation status --- */
$statusTotal = array_sum($statusValues);
$statusSlices = array();
$sliceColors = array("#1f6f43", "#2f8f5b", "#7fbf9b", "#b7dcc6", "#dcece2");
$cursor = 0;
foreach ($statusLabels as $index => $label) {
    $value = $statusValues[$index];
    $percent = $statusTotal > 0 ? ($value / $statusTotal) * 100 : 0;
    $color = $sliceColors[$index % count($sliceColors)];
    $statusSlices[] = array(
        "label" => $label,
        "value" => $value,
        "start" => $cursor,
        "end" => $cursor + $percent,
        "color" => $color
    );
    $cursor += $percent;
}

$donationTrendLabels = array();
$donationTrendValues = array();
foreach ($donationTrendRows as $row) {
    $donationTrendLabels[] = $row["donation_date"];
    $donationTrendValues[] = (int)$row["total_donations"];
}
$donationTrendMax = count($donationTrendValues) > 0 ? max($donationTrendValues) : 0;

$quantityTrendMax = count($quantityTrendValues) > 0 ? max($quantityTrendValues) : 0;

?>

<!DOCTYPE html>
<html>

<head>

    <title>Impact Summary</title>

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
            height: 200px;
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
            min-width: 42px;
        }

        .bar-fill {
            width: 65%;
            background: linear-gradient(180deg, #2f8f5b, #1f6f43);
            border-radius: 6px 6px 0 0;
            min-height: 2px;
        }

        .bar-value {
            font-size: 11px;
            color: #1f6f43;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .bar-label {
            font-size: 10px;
            color: #68736b;
            margin-top: 6px;
            text-align: center;
        }

        .line-chart {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            height: 180px;
            padding-top: 10px;
            border-bottom: 2px solid #dfe9e2;
            overflow-x: auto;
        }

        .line-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            height: 100%;
            flex: 1;
            min-width: 42px;
        }

        .line-point {
            width: 11px;
            height: 11px;
            background: #1f6f43;
            border-radius: 50%;
            margin-bottom: 4px;
        }

        .line-stem {
            width: 3px;
            background: #b7dcc6;
            min-height: 2px;
        }

        .line-label {
            font-size: 10px;
            color: #68736b;
            margin-top: 6px;
            text-align: center;
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

    <div class="logo">ZERO HUNGER</div>

    <div class="nav-links">

        <a href="index.php">Home</a>
        <a href="showPartners.php">Partners</a>
        <a href="showMissions.php">Rescue Missions</a>
        <a href="showImpact.php">Community Impact</a>

    </div>

</nav>

<div class="container">

    <h1 class="page-title">
        Community Impact Summary
    </h1>

    <p>
        An overview of total food rescue activity: how many donations came in,
        how much food they contained, how many donors contributed, and how
        that activity has moved over time.
    </p>

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-label">Total Donations</div>
            <div class="kpi-value"><?php echo $totalDonations; ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Total Food Items</div>
            <div class="kpi-value"><?php echo $totalFoodItems; ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Total Food Quantity Rescued</div>
            <div class="kpi-value"><?php echo $totalFoodQuantity; ?></div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Total Donors</div>
            <div class="kpi-value"><?php echo $totalDonors; ?></div>
        </div>

    </div>

    <div class="chart-grid">

        <div class="chart-card">
            <h3>Donation Status Breakdown</h3>

            <?php if (count($statusSlices) > 0) { ?>
                <div class="pie-wrap">

                    <div class="pie-chart" style="background: conic-gradient(
                        <?php
                        $sliceParts = array();
                        foreach ($statusSlices as $slice) {
                            $sliceParts[] = $slice["color"] . " " . round($slice["start"], 2) . "% " . round($slice["end"], 2) . "%";
                        }
                        echo implode(", ", $sliceParts);
                        ?>
                    );"></div>

                    <ul class="pie-legend">
                        <?php foreach ($statusSlices as $slice) { ?>
                            <li>
                                <span class="legend-swatch" style="background: <?php echo $slice["color"]; ?>;"></span>
                                <?php echo htmlspecialchars($slice["label"]); ?>
                                (<?php echo (int)$slice["value"]; ?>)
                            </li>
                        <?php } ?>
                    </ul>

                </div>
            <?php } else { ?>
                <p>No donation status data found.</p>
            <?php } ?>
        </div>

        <div class="chart-card">
            <h3>Donations Per Date</h3>

            <?php if (count($donationTrendLabels) > 0) { ?>
                <div class="line-chart">
                    <?php foreach ($donationTrendLabels as $index => $label) {
                        $value = $donationTrendValues[$index];
                        $heightPercent = $donationTrendMax > 0 ? ($value / $donationTrendMax) * 100 : 0;
                    ?>
                        <div class="line-column">
                            <div class="line-point"></div>
                            <div class="line-stem" style="height: <?php echo round($heightPercent, 2); ?>%;"></div>
                            <div class="line-label"><?php echo htmlspecialchars($label); ?></div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>No donation trend data found.</p>
            <?php } ?>
        </div>

        <div class="chart-card">
            <h3>Food Quantity Rescued Per Date</h3>

            <?php if (count($quantityTrendLabels) > 0) { ?>
                <div class="bar-chart">
                    <?php foreach ($quantityTrendLabels as $index => $label) {
                        $value = $quantityTrendValues[$index];
                        $heightPercent = $quantityTrendMax > 0 ? ($value / $quantityTrendMax) * 100 : 0;
                    ?>
                        <div class="bar-column">
                            <div class="bar-value"><?php echo (int)$value; ?></div>
                            <div class="bar-fill" style="height: <?php echo round($heightPercent, 2); ?>%;"></div>
                            <div class="bar-label"><?php echo htmlspecialchars($label); ?></div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>No food quantity trend data found.</p>
            <?php } ?>
        </div>

        <div class="chart-card">
            <h3>What This Shows</h3>
            <p>
                The status chart shows how much rescued food is still available
                versus already allocated to a recipient.
            </p>
            <br>
            <p>
                The two trend charts compare the number of donations received
                on each date against the actual quantity of food that came
                with those donations, on the same dates.
            </p>
        </div>

    </div>

    <h2 class="page-title" style="font-size: 24px;">Donation Trend Detail</h2>

    <table>

        <tr>
            <th>Donation Date</th>
            <th>Total Donations</th>
        </tr>

        <?php if (count($donationTrendRows) > 0) { ?>
            <?php foreach ($donationTrendRows as $row) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["donation_date"]); ?></td>
                    <td><?php echo htmlspecialchars($row["total_donations"]); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="2">No donation records found.</td>
            </tr>
        <?php } ?>

    </table>

</div>

</body>

</html>

<?php
$conn->close();
?>
