<?php

require_once "DBconnect.php";

$sql = "SELECT v.volunteer_id,v.name,v.phone,v.area,v.availability,va.assignment_id,va.assignment_time,va.location FROM Volunteer v
JOIN Volunteer_Assignment va ON v.volunteer_id = va.volunteer_id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Volunteer Impact</title>

    <link rel="stylesheet" href="css/style.css">

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
        Volunteer Impact Records
    </h1>

    <table>

        <tr>
            <th>Volunteer ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Area</th>
            <th>Availability</th>
            <th>Assignment ID</th>
            <th>Assignment Time</th>
            <th>Location</th>
        </tr>

        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

        ?>

        <tr>

            <td><?php echo $row["volunteer_id"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
            <td><?php echo $row["phone"]; ?></td>
            <td><?php echo $row["area"]; ?></td>
            <td><?php echo $row["availability"]; ?></td>
            <td><?php echo $row["assignment_id"]; ?></td>
            <td><?php echo $row["assignment_time"]; ?></td>
            <td><?php echo $row["location"]; ?></td>

        </tr>

        <?php

            }

        }
        else {

            echo "<tr>
                    <td colspan='8'>
                        No volunteer impact records found.
                    </td>
                  </tr>";

        }

        ?>

    </table>

</div>

</body>

</html>