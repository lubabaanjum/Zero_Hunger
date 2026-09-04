<?php

require_once "DBconnect.php";

$sql = "SELECT a.allocation_id,a.quantity,a.date,d.distribution_id,d.mission_id,d.pickup_time,d.delivery_time,d.status
FROM Resource_Allocation a JOIN Distribution d ON a.allocation_id = d.allocation_id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Distribution Impact</title>

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
        Distribution Impact Records
    </h1>

    <table>

        <tr>
            <th>Allocation ID</th>
            <th>Quantity</th>
            <th>Allocation Date</th>
            <th>Distribution ID</th>
            <th>Mission ID</th>
            <th>Pickup Time</th>
            <th>Delivery Time</th>
            <th>Status</th>
        </tr>

        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

        ?>

        <tr>

            <td><?php echo $row["allocation_id"]; ?></td>

            <td><?php echo $row["quantity"]; ?></td>

            <td><?php echo $row["date"]; ?></td>

            <td><?php echo $row["distribution_id"]; ?></td>

            <td><?php echo $row["mission_id"]; ?></td>

            <td><?php echo $row["pickup_time"]; ?></td>

            <td><?php echo $row["delivery_time"]; ?></td>

            <td><?php echo $row["status"]; ?></td>

        </tr>

        <?php

            }

        }
        else {

            echo "<tr>
                    <td colspan='8'>
                        No distribution impact records found.
                    </td>
                  </tr>";

        }

        ?>

    </table>

</div>

</body>

</html>