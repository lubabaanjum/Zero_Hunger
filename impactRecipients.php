<?php

require_once "DBconnect.php";

$sql = "SELECT r.recipient_id,r.org_name,r.location,r.priority_level,r.capacity,COUNT(a.allocation_id) AS total_allocations,SUM(a.quantity) AS total_allocated_quantity,
COUNT(DISTINCT d.donation_id) AS total_donations FROM Recipient_Organization r JOIN Resource_Allocation a ON r.recipient_id = a.recipient_id
LEFT JOIN Food_Donation d ON a.donation_id = d.donation_id GROUP BY r.recipient_id,r.org_name,r.location,r.priority_level,r.capacity
HAVING COUNT(a.allocation_id) >= 1 ORDER BY total_allocated_quantity DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Recipient Allocation Impact</title>

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

        <a href="showRecipientRequirements.php">
            Requirements
        </a>

    </div>

</nav>


<div class="container">

    <h1 class="page-title">
        Recipient Allocation Impact
    </h1>

    <p>
        This report shows the allocation activity and total
        food quantity received by each recipient organization.
    </p>


    <table>

        <tr>

            <th>Recipient ID</th>

            <th>Recipient Organization</th>

            <th>Location</th>

            <th>Priority Level</th>

            <th>Capacity</th>

            <th>Total Allocations</th>

            <th>Total Allocated Quantity</th>

            <th>Total Donations</th>

        </tr>


        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

        ?>

        <tr>

            <td>
                <?php
                echo $row["recipient_id"];
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row["org_name"]);
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row["location"]);
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row["priority_level"]);
                ?>
            </td>


            <td>
                <?php
                echo $row["capacity"];
                ?>
            </td>


            <td>
                <?php
                echo $row["total_allocations"];
                ?>
            </td>


            <td>
                <?php
                echo $row["total_allocated_quantity"];
                ?>
            </td>


            <td>
                <?php
                echo $row["total_donations"];
                ?>
            </td>

        </tr>

        <?php

            }

        }
        else {

            echo "<tr>
                    <td colspan='8'>
                        No recipient allocation data found.
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