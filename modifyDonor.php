<?php
require_once "DBconnect.php";
$id = $_GET["id"];
$sql = "SELECT * FROM Donor WHERE donor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$donor = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modify Donor</title>
</head>
<body>
<h1>Modify Donor</h1>
<form action="updateDonor.php" method="POST">
    <input type="hidden" name="donor_id"
           value="<?php echo $donor["donor_id"]; ?>">
    <label>Name:</label>
    <input type="text" name="name"
           value="<?php echo $donor["name"]; ?>">
    <br><br>
    <label>Phone:</label>
    <input type="text" name="phone"
           value="<?php echo $donor["phone"]; ?>">
    <br><br>
    <label>Email:</label>
    <input type="email" name="email"
           value="<?php echo $donor["email"]; ?>">
    <br><br>
    <label>ZIP:</label>
    <input type="text" name="zip"
           value="<?php echo $donor["zip"]; ?>">
    <br><br>
    <label>Ward:</label>
    <input type="text" name="ward"
           value="<?php echo $donor["ward"]; ?>">
    <br><br>
    <label>Postal Card:</label>
    <input type="text" name="postal_card"
           value="<?php echo $donor["postal_card"]; ?>">
    <br><br>
    <button type="submit">Update Donor</button>
</form>
</body>
</html>