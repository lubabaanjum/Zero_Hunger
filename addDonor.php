<!DOCTYPE html>
<html>
<head>
    <title>Add Donor</title>
</head>
<body>
    <h1>Add Donor</h1>
    <form action="insertDonor.php" method="POST">
        <label>Name:</label>
        <input type="text" name="name">
        <br><br>
        <label>Phone:</label>
        <input type="text" name="phone">
        <br><br>
        <label>Email:</label>
        <input type="email" name="email">
        <br><br>
        <label>ZIP:</label>
        <input type="text" name="zip">
        <br><br>
        <label>Ward:</label>
        <input type="text" name="ward">
        <br><br>
        <label>Postal Card:</label>
        <input type="text" name="postal_card">
        <br><br>
        <button type="submit">Add Donor</button>
     </form>
</body>
</html>