<?php
require_once "DBconnect.php";
$allocation_sql = "SELECT allocation_id FROM Resource_Allocation ORDER BY allocation_id ASC";
$allocation_result = $conn->query($allocation_sql);
$mission_sql = "SELECT mission_id, mission_name FROM Rescue_Mission ORDER BY mission_id ASC";
$mission_result = $conn->query($mission_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Distribution</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">ZERO HUNGER</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showLifecycle.php">Lifecycle</a>
        <a href="showDistributions.php">Distributions</a>
    </div>
</nav>
<div class="form-container">
    <h1 class="page-title">
        Add Distribution
    </h1>
    <form action="insertDistribution.php"
          method="POST">
       <div class="form-group">
            <label>Allocation ID:</label>
            <select name="allocation_id" required>
                <option value="">
                    -- Select Allocation --
                </option>
                <?php
                if($allocation_result->num_rows > 0)
                {
                    while($allocation =
                          $allocation_result->fetch_assoc())
                    {
                ?>
                    <option value="<?php
                        echo $allocation["allocation_id"];
                    ?>">
                        Allocation ID:
                        <?php
                        echo $allocation["allocation_id"];
                        ?>
                    </option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Mission:</label>
            <select name="mission_id">
                <option value="">
                    No Mission
                </option>
                <?php
                if($mission_result->num_rows > 0)
                {
                    while($mission =
                          $mission_result->fetch_assoc())
                    {
                ?>
                    <option value="<?php
                        echo $mission["mission_id"];
                    ?>">
                        <?php
                        echo $mission["mission_id"];
                        ?>
                        -
                        <?php
                        echo $mission["mission_name"];
                        ?>
                    </option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn"> Create Distribution </button>
    </form>
</div>
</body>
</html>
<?php
$conn->close();
?>