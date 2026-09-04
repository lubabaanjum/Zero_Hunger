<!DOCTYPE html>
<html>
<head>
   <title>Add Recipient Organization</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">ZERO HUNGER</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showDonors.php">Donors</a>
        <a href="showRecipients.php">Recipients</a>
    </div>
</nav>
<div class="form-container">
    <h1 class="page-title">Add Recipient Organization</h1>
    <form action="insertRecipient.php" method="POST">
        <div class="form-group">
            <label>Organization Name:</label>
            <input type="text"
                   name="org_name"
                   required>
        </div>
        <div class="form-group">
            <label>Location:</label>
            <input type="text"
                   name="location"
                   required>
        </div>
        <div class="form-group">
            <label>Priority Level:</label>
            <input type="text"
                   name="priority_level"
                   required>
        </div>
        <div class="form-group">
            <label>Capacity:</label>
            <input type="number"
                   name="capacity"
                   required>
        </div>
        <button type="submit" class="btn">
            Add Recipient
        </button>
    </form>
</div>
</body>
</html>