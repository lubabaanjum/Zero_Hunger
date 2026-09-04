<?php
session_start();

if(!isset($_SESSION["user_id"])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zero Hunger</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="sidebar">
    <div class="sidebar-user">
    <?php echo htmlspecialchars($_SESSION["username"]); ?>
    <br>
    <small><?php echo htmlspecialchars($_SESSION["role"]); ?></small>
</div>
    <div class="sidebar-logo">
        ZERO HUNGER</div>
    <a href="index.php">Home</a>
    <a href="showDonors.php">Donors</a>
    <a href="showRecipients.php">Recipients</a>
    <div class="sidebar-heading">
        Food Management
    </div>
    <a href="showFoodItems.php">Food Items</a>
    <a href="expiredFood.php">Expired Food</a>
    <a href="expiringSoon.php">Expiring Soon</a>
    <a href="showInspections.php">Food Inspections</a>
    <div class="sidebar-heading">
        Volunteer Management
    </div>
    <a href="showVolunteers.php">Volunteers</a>
    <a href="assignVolunteer.php">Assign Volunteer</a>
    <a href="showAssignments.php">Assignments</a>
    <a href="findSuitableVolunteer.php">
        Find Suitable Volunteer
    </a>
    <a href="volunteerWorkload.php">
        Workload Analysis
    </a>
    <div class="sidebar-heading">
        Distribution
    </div>
    <a href="showLifecycle.php">
        Distribution Lifecycle
    </a>
    <a href="showDistributionDetails.php">
        Distribution Details
    </a>
    <a href="updateDistributionStatus.php">
        Update Status
    </a>
    <div class="sidebar-heading">
        Rescue & Allocation
    </div>
    <a href="showMission.php">
        Rescue Missions
    </a>
    <a href="showAllocations.php">
        Resource Allocation
    </a>
    <div class="sidebar-heading">
        Feedback
    </div>
    <a href="showFeedback.php">
        View Feedback
    </a>
    <a href="addFeedback.php">
        Add Feedback
    </a>
    <a href="showCompleted.php">
        Completed Distributions
    </a>
    <div class="sidebar-heading">
        Analytics
    </div>
    <a href="showImpact.php">
        Community Impact
    </a>
    <a href="searchFoodItems.php">
        Search Food
    </a>
  <a href="logout.php" class="logout-btn">Logout</a>
</div>
<div class="main-content">
<section class="hero">
    <h1>Zero Hunger</h1>
    <p>
        Food Rescue and Distribution Management System
    </p>
    <p>
        Reducing food waste and connecting surplus food
        with communities in need.
    </p>
</section>
<section class="features">
    <h2>Features</h2>
    <div class="feature-container">
        <div class="feature-card">
            <h3>Donor & Recipient Management</h3>
            <p>
                Manage donors, recipients
                and recipient requirements.
            </p>
            <a href="showDonors.php" class="btn">
                Donors
            </a>
            <a href="showRecipients.php" class="btn">
                Recipients
            </a>
        </div>
        <div class="feature-card">
            <h3>
                Feedback & Completion
            </h3>
            <p>
                View feedback and completed
                distribution records.
            </p>
            <a href="showFeedback.php" class="btn">
                Open
            </a>
        </div>
        <div class="feature-card">
            <h3>
                Food Inspection
            </h3>
            <p>
                Monitor food items, expiry dates
                and inspection records.
            </p>
            <a href="showFoodItems.php" class="btn">
                Open
            </a>
        </div>
        <div class="feature-card">
            <h3>
                Volunteer Management
            </h3>
            <p>
                Manage volunteers and
                their mission assignments.
            </p>
            <a href="showVolunteers.php" class="btn">
                Open
            </a>
        </div>
        <div class="feature-card">
            <h3>
                Search & Filter
            </h3>
            <p>
                Search available food by food name
                or pickup location and view donor information.
            </p>
            <a href="searchFoodItems.php" class="btn">
                Search Food
            </a>
            <a href="searchRecipients.php" class="btn">
                Search Recipients
            </a>
        </div>
        <div class="feature-card">
    <h3>
        Distribution Lifecycle
    </h3>
    <p>
        Track food distributions from
        donation and allocation to delivery.
    </p>
    <a href="distributionMenu.php" class="btn">
        Open
    </a>
</div>
        <div class="feature-card">
            <h3>
                Rescue Mission Coordination
            </h3>
            <p>
                Create, manage, update and
                coordinate community rescue missions.
            </p>
            <a href="showMission.php" class="btn">
                Open
            </a>
        </div>
        <div class="feature-card">
            <h3>
                Sustainability & Impact Analytics
            </h3>
            <p>
                Analyze rescue missions, food,
                donations, distributions and community impact.
            </p>
            <a href="showImpact.php" class="btn">
                Open
            </a>
        </div>
        <div class="feature-card">
            <h3>
                Food Rescue & Resource Matching
            </h3>
            <p>
                Allocate rescued food to recipients
                and manage resource distribution.
            </p>
            <a href="showAllocations.php" class="btn">
                Open
            </a>
        </div>
    </div>
</section>
<footer>
    <p>
        Zero Hunger © 2026
    </p>
</footer>
</div>
</body>
</html>