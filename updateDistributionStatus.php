<?php
require_once "DBconnect.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Distribution Status</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">ZERO HUNGER</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showDistributions.php">Distributions</a>
        <a href="showLifecycle.php">Lifecycle</a>
    </div>
</nav>
<div class="form-container">
    <h1 class="page-title">Update Distribution Status</h1>
    <form method="POST">
        <div class="form-group">
            <label>Distribution ID:</label>
            <input type="number"
                   name="distribution_id"
                   required>
        </div>
        <div class="form-group">
            <label>New Status:</label>
            <select name="status" required>
                <option value="">Select Status</option>
                <option value="Pending">Pending</option>
                <option value="Picked Up">Picked Up</option>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div>
        <button type="submit" class="btn">
            Update Status
        </button>
    </form>
</div>
<?php
if(isset($_POST["distribution_id"]))
{
    $distribution_id = $_POST["distribution_id"];
    $status = $_POST["status"];

    if($status == "Pending")
    {
        $sql = "UPDATE Distribution
                SET status='Pending'
                WHERE distribution_id='$distribution_id'";
    }
    elseif($status == "Picked Up")
    {
        $sql = "UPDATE Distribution
                SET status='Picked Up',
                    pickup_time=IFNULL(pickup_time, NOW())
                WHERE distribution_id='$distribution_id'";
    }
    elseif($status == "In Transit")
    {
        $sql = "UPDATE Distribution
                SET status='In Transit',
                    pickup_time=IFNULL(pickup_time, NOW())
                WHERE distribution_id='$distribution_id'";
    }
    elseif($status == "Delivered")
    {
        $sql = "UPDATE Distribution
                SET status='Delivered',
                    pickup_time=IFNULL(pickup_time, NOW()),
                    delivery_time=IFNULL(delivery_time, NOW())
                WHERE distribution_id='$distribution_id'";
    }
    if($conn->query($sql))
    {echo "<div class='container'>";
        echo "Distribution status updated successfully!";
        echo "<br><br>";
        echo "<a href='showLifecycle.php'> Distribution Lifecycle </a>";
        echo "</div>";
    }
    else
    {
        echo "<div class='container'>";

        echo "Error: " . $conn->error;

        echo "</div>";
    }
}
$conn->close();
?>
</body>
</html>