<?php

require_once "DBconnect.php";

$sql = "SELECT p.partner_id, p.organization_name, p.location, COUNT(m.mission_id) AS total_missions, MIN(m.mission_date) AS first_mission,
MAX(m.mission_date) AS latest_mission FROM Partner_Organization p LEFT JOIN Rescue_Mission m ON p.partner_id = m.partner_id
WHERE p.organization_name IS NOT NULL AND p.organization_name <> '' GROUP BY p.partner_id, p.organization_name, p.location
ORDER BY total_missions DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Mission Impact Analysis</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>


<nav class="navbar">

    <div class="logo">
        ZERO HUNGER
    </div>

    <div class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="showMission.php">
            Rescue Missions
        </a>

        <a href="showAllocations.php">
            Allocations
        </a>

        <a href="showPartners.php">
            Partners
        </a>

    </div>

</nav>


<div class="container">

    <h1 class="page-title">
        Rescue Mission Impact Analysis
    </h1>

    <p>
        This report shows the rescue mission activity
        handled by each partner organization, including
        partner organizations that have not yet taken
        part in any rescue mission.
    </p>


    <table>

        <tr>

            <th>Partner ID</th>

            <th>Partner Organization</th>

            <th>Location</th>

            <th>Total Missions</th>

            <th>First Mission</th>

            <th>Latest Mission</th>

        </tr>


        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

        ?>

        <tr>

            <td>
                <?php
                echo $row["partner_id"];
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row["organization_name"]);
                ?>
            </td>


            <td>
                <?php

                if (
                    $row["location"] !== null &&
                    $row["location"] !== ""
                ) {

                    echo htmlspecialchars(
                        $row["location"]
                    );

                } else {

                    echo "Not Provided";

                }

                ?>
            </td>


            <td>
                <?php
                echo $row["total_missions"];
                ?>
            </td>


            <td>
                <?php

                if ($row["first_mission"] !== null) {
                    echo $row["first_mission"];
                } else {
                    echo "No missions yet";
                }

                ?>
            </td>


            <td>
                <?php

                if ($row["latest_mission"] !== null) {
                    echo $row["latest_mission"];
                } else {
                    echo "No missions yet";
                }

                ?>
            </td>

        </tr>

        <?php

            }

        } else {

            echo "<tr>

                    <td colspan='6'>
                        No partner organization data found.
                    </td>

                  </tr>";

        }

        ?>

    </table>


</div>


</body>

</html>


<?php

$conn->close();

?>
