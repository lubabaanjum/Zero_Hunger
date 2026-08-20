<?php
require_once "DBconnect.php";
$sql = "SELECT * FROM Volunteer";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Volunteers</title>
</head>
<body>
<h1>Volunteers</h1>
<a href="assignVolunteer.php">Assign Volunteer</a>
<br><br>
<table border="1" cellpadding="10">
<tr>
    <th>Volunteer ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
</tr>
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?php echo $row["volunteer_id"]; ?></td>
    <td><?php echo $row["name"]; ?></td>
    <td><?php echo $row["phone"]; ?></td>
    <td><?php echo $row["email"]; ?></td>
</tr>
<?php
}} 
else{
    echo "<tr><td colspan='4'>No volunteers found.</td></tr>";}
?>
</table>
</body>
</html>