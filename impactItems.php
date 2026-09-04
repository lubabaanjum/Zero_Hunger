<?php

require_once "DBconnect.php";

$sql = "SELECT d.donation_id,d.donation_date,f.item_name,f.quantity,f.shelf_life
FROM Food_donation d JOIN Food_item f ON d.donation_id = f.donation_id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Food Impact Records</title>

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
        Food Impact Records
    </h1>

    <table>

        <tr>
            <th>Donation ID</th>
            <th>Donation Date</th>
            <th>Food Item</th>
            <th>Quantity</th>
            <th>Shelf Life</th>
        </tr>

        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

        ?>

        <tr>

            <td><?php echo $row["donation_id"]; ?></td>

            <td><?php echo $row["donation_date"]; ?></td>

            <td><?php echo $row["item_name"]; ?></td>

            <td><?php echo $row["quantity"]; ?></td>

            <td><?php echo $row["shelf_life"]; ?></td>

        </tr>

        <?php

            }

        }
        else {

            echo "<tr>
                    <td colspan='5'>
                        No food impact records found.
                    </td>
                  </tr>";

        }

        ?>

    </table>

</div>

</body>

</html>