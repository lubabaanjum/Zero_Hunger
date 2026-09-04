<!DOCTYPE html>
<html>
<head>
<title>Add Donor</title>
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
    <h1 class="page-title">Add Donor</h1>
    <form action="insertDonor.php" method="POST">
        <div class="form-group">
            <label>Donor Name:</label>
            <input type="text"
                   name="name"
                   required>
        </div>
        <div class="form-group">
            <label>Phone:</label>
            <input type="text"
                   name="phone"
                   required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email"
                   name="email"
                   required>
        </div>
        <div class="form-group">
            <label>Location:</label>
            <input type="text"
                   name="location"
                   required>
        </div>
        <button type="submit" class="btn">
            Add Donor
        </button>
    </form>
</div>
</body>
</html>