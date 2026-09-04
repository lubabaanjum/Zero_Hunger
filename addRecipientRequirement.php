<!DOCTYPE html>
<html>

<head>

    <title>Add Recipient Requirement</title>

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

    <h1 class="page-title">Add Recipient Requirement</h1>

    <form action="insertRecipientRequirement.php" method="POST">

        <div class="form-group">

            <label>Food Needed:</label>

            <input type="text"
                   name="food_needed"
                   required>

        </div>

        <div class="form-group">

            <label>Urgency Level:</label>

            <input type="text"
                   name="urgency_level"
                   required>

        </div>

        <div class="form-group">

            <label>Quantity:</label>

            <input type="number"
                   name="quantity"
                   required>

        </div>

        <div class="form-group">

            <label>Recipient ID:</label>

            <input type="number"
                   name="recipient_id"
                   required>

        </div>

        <button type="submit" class="btn">
            Add Requirement
        </button>

    </form>

</div>

</body>

</html>