<!DOCTYPE html>
<html>
<head>
    <title>Add Food Inspection</title>
</head>
<body>
<h1>Add Food Inspection</h1>
<form action="insertInspection.php" method="POST">
    <label>Item ID:</label>
    <input type="number" name="item_id" required>
    <br><br>
    <label>Inspection Date:</label>
    <input type="date" name="inspection_date" required>
    <br><br>
    <label>Quality Status:</label>
    <input type="text" name="quality_status" required>
    <br><br>
    <label>Remarks:</label>
    <textarea name="remarks"></textarea>
    <br><br>
    <button type="submit">Add Inspection</button>
</form>
</body>
</html>