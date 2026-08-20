<!DOCTYPE html>
<html>
<head>
    <title>Assign Volunteer</title>
</head>
<body>
<h1>Add Volunteer Assignment</h1>
<form action="insertAssignment.php" method="POST">
    <label>Volunteer ID:</label>
    <input type="number" name="volunteer_id" required>
    <br><br>
    <label>Assignment Time:</label>
    <input type="datetime-local" name="assignment_time" required>
    <br><br>
    <label>Location:</label>
    <input type="text" name="location" required>
    <br><br>
    <button type="submit">
        Assign Volunteer
    </button>
</form>
</body>
</html>