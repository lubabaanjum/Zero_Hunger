<?php
require_once "DBconnect.php";
$sql = "SELECT d.distribution_id,d.pickup_time,d.delivery_time,d.status,a.allocation_id,a.quantity,r.org_name FROM Distribution d JOIN Resource_Allocation a  ON d.allocation_id = a.allocation_id JOIN Recipient_Organization r ON a.recipient_id = r.recipient_id WHERE d.status = 'Completed' ORDER BY d.delivery_time DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Distributions | Zero Hunger</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="navbar">
    <div class="logo">    ZERO HUNGER
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showDonors.php">Donors</a>
        <a href="showRecipients.php">Recipients</a>
        <a href="showFoodItems.php">Food</a>
        <a href="showVolunteers.php">Volunteers</a>
        <a href="showFeedback.php">Feedback</a>
    </div>
</div>
<div class="container">
    <h1 class="page-title">
        Completed Distribution Records
    </h1>
    <div class="action-buttons">
        <a href="showFeedback.php" class="btn">
            View Feedback
        </a>
        <a href="addFeedback.php" class="btn">
            Add Feedback
        </a>
    </div>
    <?php if ($result && $result->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Distribution ID</th>
                    <th>Recipient Organization</th>
                    <th>Allocated Quantity</th>
                    <th>Pickup Time</th>
                    <th>Delivery Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($row["distribution_id"]); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row["org_name"]); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row["quantity"]); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row["pickup_time"]); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row["delivery_time"]); ?>
</td>

                        <td>
                            <?php echo htmlspecialchars($row["status"]); ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div class="success-note">
            No completed distribution records are currently available.
        </div>
    <?php } ?>
</div>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
<footer>
    Zero Hunger Network | Food Rescue & Distribution Management System
</footer>
</body>
</html>
<?php
$conn->close();
?>