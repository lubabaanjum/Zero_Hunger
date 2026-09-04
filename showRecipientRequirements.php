<?php

require_once "DBconnect.php";

$sql = "SELECT rr.requirement_id, rr.food_needed, rr.urgency_level, rr.quantity, rr.recipient_id, r.org_name, r.priority_level,
r.capacity FROM Recipient_requirement rr JOIN Recipient_Organization r ON rr.recipient_id = r.recipient_id";
$result = $conn->query($sql);
$urgency_sql = "SELECT SUM(urgency_level = 'High') AS high_count,SUM(urgency_level = 'Medium') AS medium_count,
SUM(urgency_level = 'Low') AS low_count FROM Recipient_requirement";
$urgency_result = $conn->query($urgency_sql);
$urgency = $urgency_result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recipient Requirements</title>
    <link rel="stylesheet" href="css/style.css">
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
    <h1 class="page-title" style="text-align:center;">
        Requirement Overview
    </h1>
    <div class="urgency-summary">
        <div class="urgency-card">
            <div class="urgency-icon">⚠️</div>
            <h2>
                <?php echo $urgency["high_count"]; ?>
            </h2>
            <p>High Urgency</p>
        </div>
        <div class="urgency-card">
            <div class="urgency-icon">🟡</div>
            <h2>
                <?php echo $urgency["medium_count"]; ?>
            </h2>
            <p>Medium Urgency</p>
        </div>
        <div class="urgency-card">
            <div class="urgency-icon">🟢</div>
            <h2>
                <?php echo $urgency["low_count"]; ?>
            </h2>
            <p>Low Urgency</p>
        </div>
    </div>
    <h1 class="page-title">
        Recipient Requirements
    </h1>
    <table>
        <tr>
            <th>Requirement ID</th>
            <th>Recipient ID</th>
            <th>Organization</th>
            <th>Food Needed</th>
            <th>Urgency Level</th>
            <th>Quantity</th>
            <th>Priority Level</th>
            <th>Capacity</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row["requirement_id"]; ?></td>
            <td><?php echo $row["recipient_id"]; ?></td>
            <td><?php echo $row["org_name"]; ?></td>
            <td><?php echo $row["food_needed"]; ?></td>
            <td><?php echo $row["urgency_level"]; ?></td>
            <td><?php echo $row["quantity"]; ?></td>
            <td><?php echo $row["priority_level"]; ?></td>
            <td><?php echo $row["capacity"]; ?></td>
        </tr>
        <?php
            }
        }
        else {
            echo "<tr>
                    <td colspan='8'>No recipient requirements found.</td>
                  </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>