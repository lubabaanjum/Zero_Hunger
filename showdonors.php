<?php
require_once "DBconnect.php";
$sql = "SELECT * FROM Donor";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Donors</title>
</head>
<body>
<h1>Donors</h1>
<a href="addDonor.php">Add a new Donor</a>
<br><br>
<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
    <th>ZIP</th>
    <th>Ward</th>
    <th>Postal Card</th>
    <th>Actions</th>
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
    <td><?php echo $row["zip"]; ?></td>
    <td><?php echo $row["ward"]; ?></td>
    <td><?php echo $row["postal_card"]; ?></td>
    <td>
        <a href="modifyDonor.php?id=<?php echo $row["donor_id"]; ?>">
            Edit
        </a>
        |
        <a href="deleteDonor.php?id=<?php echo $row["donor_id"]; ?>">
            Delete
        </a>
    </td>
</tr>
<?php
    }
} 
else{
    echo "<tr><td colspan='8'>No donors found.</td></tr>";
}
?>
</table>
</body>
</html>