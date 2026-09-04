<!DOCTYPE html>
<html>
<head>
    <title>Add Partner Organization</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar">

    <div class="logo">ZERO HUNGER</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showMissions.php">Rescue Missions</a>
        <a href="showPartners.php">Partners</a>
    </div>

</nav>

<div class="form-container">

    <h1 class="page-title">Add Partner Organization</h1>

    <form action="insertPartner.php" method="POST">

        <div class="form-group">
            <label>Organization Name:</label>
            <input type="text" name="organization_name" required>
        </div>

        <div class="form-group">
            <label>Contact Person:</label>
            <input type="text" name="contact_person" required>
        </div>

        <div class="form-group">
            <label>Phone:</label>
            <input type="text" name="phone" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Location:</label>
            <input type="text" name="location" required>
        </div>

        <button type="submit" class="btn">
            Add Partner
        </button>

    </form>

</div>

</body>
</html>