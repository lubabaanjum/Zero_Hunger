<?php

require_once "DBconnect.php";
$sql = "SELECT p.partner_id,p.organization_name,p.location,COUNT(m.mission_id) AS total_missions,MIN(m.mission_date) AS first_mission,
MAX(m.mission_date) AS latest_mission FROM Partner_Organization p RIGHT JOIN Rescue_Mission m ON p.partner_id = m.partner_id
GROUP BY p.partner_id, p.organization_name, p.location HAVING COUNT(m.mission_id) >= 1 ORDER BY total_missions DESC";

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

        <a href="showMissions.php">
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
        handled by each partner organization.
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

                if (
                    $row["organization_name"] !== null &&
                    $row["organization_name"] !== ""
                ) {

                    echo htmlspecialchars(
                        $row["organization_name"]
                    );

                } else {

                    echo "Not Provided";

                }

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
                echo $row["first_mission"];
                ?>
            </td>


            <td>
                <?php
                echo $row["latest_mission"];
                ?>
            </td>

        </tr>

        <?php

            }

        } else {

            echo "<tr>

                    <td colspan='6'>
                        No mission impact data found.
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
```
