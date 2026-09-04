<?php

require_once "DBconnect.php";

$sql = "SELECT d.donor_id,d.name,d.phone,d.email,f.donation_id,f.donation_date,f.status
FROM Donor d JOIN Food_donation f ON d.donor_id = f.donor_id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Donor Impact</title>

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
        Donor Impact Records
    </h1>

    <table>

        <tr>
            <th>Donor ID</th>
            <th>Donor Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Donation ID</th>
            <th>Donation Date</th>
            <th>Status</th>
        </tr>

        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

        ?>

        <tr>

            <td><?php echo $row["donor_id"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
            <td><?php echo $row["phone"]; ?></td>
            <td><?php echo $row["email"]; ?></td>
            <td><?php echo $row["donation_id"]; ?></td>
            <td><?php echo $row["donation_date"]; ?></td>
            <td><?php echo $row["status"]; ?></td>

        </tr>

        <?php

            }

        }
        else {

            echo "<tr>
                    <td colspan='7'>
                        No donor impact records found.
                    </td>
                  </tr>";

        }

        ?>

    </table>

</div>

</body>

</html>