<!DOCTYPE html>
<html>

<head>

    <title>Add Rescue Mission</title>

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

    <h1 class="page-title">Add Rescue Mission</h1>

    <form action="insertMission.php" method="POST">

        <div class="form-group">

            <label>Mission Name:</label>

            <input type="text"
                   name="mission_name"
                   required>

        </div>


        <div class="form-group">

            <label>Mission Date:</label>

            <input type="date"
                   name="mission_date"
                   required>

        </div>


        <div class="form-group">

            <label>Status:</label>

            <input type="text"
                   name="status"
                   required>

        </div>


        <div class="form-group">

            <label>Partner ID:</label>

            <input type="number"
                   name="partner_id"
                   required>

        </div>


        <button type="submit" class="btn">
            Add Rescue Mission
        </button>

    </form>

</div>

</body>

</html>