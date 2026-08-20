<!DOCTYPE html>
<html>
<head>
    <title>Add Feedback</title>
</head>
<body>
<h1>Give Feedback</h1>
<form action="insertFeedback.php" method="POST">
    <label>Rating:</label>
    <input type="number" name="rating" min="1" max="5" required>
    <br><br>
    <label>Comments:</label>
    <textarea name="comments"></textarea>
    <br><br>
    <label>Date:</label>
    <input type="date" name="date" required>
    <br><br>
    <label>Distribution ID:</label>
    <input type="number" name="distribution_id" required>
    <br><br>
    <label>Recipient ID:</label>
    <input type="number" name="recipient_id" required>
    <br><br>
    <button type="submit">Submit Feedback</button>
</form>
</body>
</html>