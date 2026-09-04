<?php
require_once "DBconnect.php";
$matchingSql = "SELECT r.recipient_id,r.org_name,r.location, r.priority_level, rr.food_needed,rr.urgency_level,rr.quantity 
AS required_quantity, SUM(a.quantity) AS allocated_quantity FROM Recipient_Organization r
JOIN Recipient_Requirement rr ON r.recipient_id = rr.recipient_id LEFT JOIN Resource_Allocation a ON r.recipient_id = a.recipient_id 
GROUP BY r.recipient_id,r.org_name,r.location,r.priority_level,rr.requirement_id,rr.food_needed,rr.urgency_level, rr.quantity 
ORDER BY r.priority_level, rr.urgency_level";

$matchingResult = $conn->query($matchingSql);
$matchingRows = array();
if ($matchingResult) {
    while ($row = $matchingResult->fetch_assoc()) {
        $matchingRows[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Priority & Requirement Matching</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .analysis-section { margin-top:55px; padding-top:35px; border-top:3px solid #dfe9e2; }
        .analysis-heading { font-family:Georgia,"Times New Roman",serif; color:#1f6f43; font-size:32px; margin-bottom:8px; }
        .analysis-subtitle { color:#68736b; margin-bottom:25px; font-size:16px; }
        .priority-note { background:#eef8f1; border-left:5px solid #2f8f5b; padding:16px 18px; border-radius:7px; margin:20px 0 30px; color:#315442; line-height:1.6; }
        .priority-table { width:100%; border-collapse:collapse; background:white; box-shadow:0 5px 18px rgba(0,0,0,.06); border-radius:10px; overflow:hidden; }
        .priority-table th { background:#24764b; color:white; padding:14px 12px; text-align:left; }
        .priority-table td { padding:13px 12px; border-bottom:1px solid #e5ebe7; }
        .priority-table tr:last-child td { border-bottom:none; }
        .priority-badge { display:inline-block; padding:5px 11px; border-radius:14px; font-weight:bold; font-size:13px; }
        .priority-high { background:#fde8e8; color:#a12626; }
        .priority-medium { background:#fff2d9; color:#8a5a00; }
        .priority-low { background:#e8f3ff; color:#245b8f; }
        .urgency-high { font-weight:bold; color:#a12626; }
        .urgency-medium { font-weight:bold; color:#8a5a00; }
        .urgency-low { font-weight:bold; color:#245b8f; }
        .back-button { display:inline-block; margin-top:25px; text-decoration:none; }
        @media(max-width:850px){ .priority-table { font-size:13px; } .priority-table th,.priority-table td { padding:9px 7px; } }
        @media(max-width:650px){ .priority-table { display:block; overflow-x:auto; white-space:nowrap; } }
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
    <h1 class="page-title">Priority & Requirement Matching</h1>
    <p>Identify recipient organizations that need food most urgently by comparing their priority level, food requirements and currently allocated quantity.</p>

    <div class="feature-buttons">
        <a href="showAllocations.php" class="btn">Back to Resource Allocation</a>
    </div>

    <section class="analysis-section">
        <h2 class="analysis-heading">Priority & Requirement Matching</h2>
        <p class="analysis-subtitle">This table compares recipient requirements with the food quantity currently allocated to the recipient.</p>

        <div class="priority-note">
            <strong>Priority guide:</strong> High-priority recipients are shown with their requirement and allocated quantity so the most needy organizations can be identified first. Urgency is also displayed to help distinguish immediate requirements from lower-urgency needs.
        </div>

        <table class="priority-table">
            <tr>
                <th>Recipient</th>
                <th>Location</th>
                <th>Priority</th>
                <th>Food Needed</th>
                <th>Urgency</th>
                <th>Required Quantity</th>
                <th>Allocated Quantity</th>
            </tr>
            <?php if (count($matchingRows) > 0) { ?>
                <?php foreach ($matchingRows as $row) { ?>
                    <?php
                    $priorityClass = strtolower($row["priority_level"]) === "high" ? "priority-high" : (strtolower($row["priority_level"]) === "medium" ? "priority-medium" : "priority-low");
                    $urgencyClass = "urgency-" . strtolower($row["urgency_level"]);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["org_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["location"]); ?></td>
                        <td><span class="priority-badge <?php echo $priorityClass; ?>"><?php echo htmlspecialchars($row["priority_level"]); ?></span></td>
                        <td><?php echo htmlspecialchars($row["food_needed"]); ?></td>
                        <td><span class="<?php echo $urgencyClass; ?>"><?php echo htmlspecialchars($row["urgency_level"]); ?></span></td>
                        <td><?php echo (int)$row["required_quantity"]; ?></td>
                        <td><?php echo $row["allocated_quantity"] === null ? 0 : (int)$row["allocated_quantity"]; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr><td colspan="7">No recipient requirement matching data found.</td></tr>
            <?php } ?>
        </table>
    </section>
</div>

<?php $conn->close(); ?>
</body>
</html>
