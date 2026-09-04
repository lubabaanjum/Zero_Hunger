<?php

require_once "DBconnect.php";

if (isset($_GET["allocation_id"])) {

    $allocation_id = $_GET["allocation_id"];

    $sql = "SELECT * FROM Resource_Allocation WHERE allocation_id = '$allocation_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

    }
    else {

        echo "Resource allocation not found.";
        echo "<br><br>";
        echo "<a href='showAllocations.php'>View Resource Allocations</a>";

        exit();

    }

}
else {

    echo "No resource allocation was selected.";
    echo "<br><br>";
    echo "<a href='showAllocations.php'>View Resource Allocations</a>";

    exit();

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Resource Allocation</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

    <div class="logo">ZERO HUNGER</div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="showMission.php">Rescue Missions</a>

        <a href="showPartners.php">Partners</a>

    </div>

</nav>


<div class="form-container">

    <h1 class="page-title">Edit Resource Allocation</h1>


    <form action="updateAllocation.php" method="POST">

        <input type="hidden"
               name="allocation_id"
               value="<?php echo $row["allocation_id"]; ?>">


        <div class="form-group">

            <label>Quantity:</label>

            <input type="number"
                   name="quantity"
                   value="<?php echo $row["quantity"]; ?>"
                   required>

        </div>


        <div class="form-group">

            <label>Date:</label>

            <input type="date"
                   name="date"
                   value="<?php echo $row["date"]; ?>"
                   required>

        </div>


        <div class="form-group">

            <label>Donation ID:</label>

            <input type="number"
                   name="donation_id"
                   value="<?php echo $row["donation_id"]; ?>"
                   required>

        </div>


        <div class="form-group">

            <label>Recipient ID:</label>

            <input type="number"
                   name="recipient_id"
                   value="<?php echo $row["recipient_id"]; ?>"
                   required>

        </div>


        <button type="submit" class="btn">

            Update Resource Allocation

        </button>

    </form>

</div>

</body>

</html>