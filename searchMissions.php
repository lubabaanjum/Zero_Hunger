<?php

require_once "DBconnect.php";

$sql="SELECT mission_id, mission_name, mission_date, status, partner_id
      FROM Rescue_Mission";

$result=$conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Search Rescue Missions</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

    <div class="logo">ZERO HUNGER</div>

    <div class="nav-links">

        <a href="index.php">Home</a>
        <a href="showDonors.php">Donors</a>
        <a href="showRecipients.php">Recipients</a>
        <a href="showMission.php">Missions</a>
        <a href="searchMissions.php">Search Missions</a>

    </div>

</nav>

<div class="container">

    <h1 class="page-title">Search Rescue Missions</h1>

    <form method="POST">

        <div class="form-group">

            <label>Mission Name:</label>

            <input type="text"
                   name="mission_name">

        </div>

        <div class="form-group">

            <label>Mission Date:</label>

            <input type="date"
                   name="mission_date">

        </div>

        <div class="form-group">

            <label>Status:</label>

            <input type="text"
                   name="status">

        </div>

        <button type="submit" class="btn">
            Search
        </button>

    </form>

    <br><br>

    <table>

        <tr>

            <th>Mission ID</th>
            <th>Mission Name</th>
            <th>Mission Date</th>
            <th>Status</th>
            <th>Partner ID</th>

        </tr>

        <?php

        $found=false;

        if($result->num_rows>0)
        {

            while($row=$result->fetch_assoc())
            {

                $show=true;

                if(isset($_POST["mission_name"]) && $_POST["mission_name"]!="")
                {
                    if(stripos($row["mission_name"], $_POST["mission_name"])===false)
                    {
                        $show=false;
                    }
                }

                if(isset($_POST["mission_date"]) && $_POST["mission_date"]!="")
                {
                    if($row["mission_date"] != $_POST["mission_date"])
                    {
                        $show=false;
                    }
                }

                if(isset($_POST["status"]) && $_POST["status"]!="")
                {
                    if(stripos($row["status"], $_POST["status"])===false)
                    {
                        $show=false;
                    }
                }

                if($show)
                {

                    $found=true;

        ?>

        <tr>

            <td><?php echo $row["mission_id"]; ?></td>
            <td><?php echo $row["mission_name"]; ?></td>
            <td><?php echo $row["mission_date"]; ?></td>
            <td><?php echo $row["status"]; ?></td>
            <td><?php echo $row["partner_id"]; ?></td>

        </tr>

        <?php

                }

            }

        }

        if(!$found)
        {

            echo "<tr>
                    <td colspan='5'>
                    No matching missions found.
                    </td>
                  </tr>";

        }

        ?>

    </table>

</div>

</body>

</html>