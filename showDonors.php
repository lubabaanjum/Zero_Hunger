<?php
require_once "DBconnect.php";
$sql = "SELECT donor_id, name, phone, email,location FROM Donor";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Donors</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">ZERO HUNGER</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showRecipients.php">Recipients</a>
    </div>
</nav>
<div class="container">
    <h1 class="page-title">Donors</h1>
    <a href="addDonor.php" class="btn"> Add Donor</a>
    <br><br>
    <table>
        <tr>
            <th>Donor ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Location</th>
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
            <td><?php echo $row["location"]; ?></td>
        </tr>
        <?php
            }
        } 
        else {
            echo "<tr>
                    <td colspan='5'>No donors found.</td>
                  </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>