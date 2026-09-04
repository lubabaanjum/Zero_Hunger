<?php

require_once "DBconnect.php";

if (isset($_GET["mission_id"])) {

    $mission_id = $_GET["mission_id"];

    $sql = "SELECT * FROM Rescue_Mission WHERE mission_id = '$mission_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

    }
    else {

        echo "Rescue mission not found.";
        echo "<br><br>";
        echo "<a href='showMission.php'>View Rescue Missions</a>";

        exit();

    }

}
else {

    echo "No rescue mission was selected.";
    echo "<br><br>";
    echo "<a href='showMission.php'>View Rescue Missions</a>";

    exit();

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Rescue Mission</title>

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

    <h1 class="page-title">Edit Rescue Mission</h1>


    <form action="updateMission.php" method="POST">


        <input type="hidden"
               name="mission_id"
               value="<?php echo $row["mission_id"]; ?>">


        <div class="form-group">

            <label>Mission Name:</label>

            <input type="text"
                   name="mission_name"
                   value="<?php echo $row["mission_name"]; ?>"
                   required>

        </div>


        <div class="form-group">

            <label>Mission Date:</label>

            <input type="date"
                   name="mission_date"
                   value="<?php echo $row["mission_date"]; ?>"
                   required>

        </div>


        <div class="form-group">

            <label>Status:</label>

            <input type="text"
                   name="status"
                   value="<?php echo $row["status"]; ?>"
                   required>

        </div>


        <div class="form-group">

            <label>Partner ID:</label>

            <input type="number"
                   name="partner_id"
                   value="<?php echo $row["partner_id"]; ?>"
                   required>

        </div>


        <button type="submit" class="btn">

            Update Rescue Mission

        </button>


    </form>

</div>

</body>

</html>