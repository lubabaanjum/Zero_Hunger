<?php
require_once "DBconnect.php";

$sql = "SELECT partner_id, organization_name, contact_person, phone, email, location
        FROM Partner_organization";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Partner Organizations</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar">

    <div class="logo">ZERO HUNGER</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showMissions.php">Rescue Missions</a>
        <a href="showPartners.php">Partners</a>
    </div>

</nav>

<div class="container">

    <h1 class="page-title">Partner Organizations</h1>

    <a href="addPartner.php" class="btn">Add Partner Organization</a>

    <br><br>

    <table>

        <tr>
            <th>Partner ID</th>
            <th>Organization Name</th>
            <th>Contact Person</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Location</th>
        </tr>

<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

?>

        <tr>

            <td><?php echo $row["partner_id"]; ?></td>

            <td><?php echo $row["organization_name"]; ?></td>

            <td><?php echo $row["contact_person"]; ?></td>

            <td><?php echo $row["phone"]; ?></td>

            <td><?php echo $row["email"]; ?></td>

            <td><?php echo $row["location"]; ?></td>

        </tr>

<?php

    }

}
else {

    echo "<tr>
            <td colspan='6'>No partner organizations found.</td>
          </tr>";

}

?>

    </table>

</div>

