<?php

require_once "DBconnect.php";

$sql = "SELECT donation_date, COUNT(donation_id) AS total_donations FROM Food_donation GROUP BY donation_date";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Impact Summary</title>

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
        Community Impact Summary
    </h1>

    <table>

        <tr>
            <th>Donation Date</th>
            <th>Total Donations</th>
        </tr>

        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

        ?>

        <tr>

            <td><?php echo $row["donation_date"]; ?></td>

            <td><?php echo $row["total_donations"]; ?></td>

        </tr>

        <?php

            }

        }
        else {

            echo "<tr>
                    <td colspan='2'>
                        No donation records found.
                    </td>
                  </tr>";

        }

        ?>

    </table>

</div>

</body>

</html>