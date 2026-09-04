<?php
require_once "DBconnect.php";
$sql="SELECT recipient_id, org_name, location, priority_level, capacity  FROM Recipient_Organization";
$result=$conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Recipients</title>
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

<div class="container">
    <h1 class="page-title">Search Recipient Organizations</h1>
    <form method="POST">
        <div class="form-group">
            <label>Organization Name:</label>
            <input type="text" name="org_name">
        </div>
        <div class="form-group">
            <label>Location:</label>
            <input type="text" name="location">
       </div>
        <div class="form-group">
            <label>Priority Level:</label>
            <input type="text"     name="priority_level">
        </div>
        <button type="submit" class="btn"> Search </button>
    </form>
    <br><br>
    <table>
        <tr>
            <th>Recipient ID</th>
            <th>Organization Name</th>
            <th>Location</th>
            <th>Priority Level</th>
            <th>Capacity</th>
        </tr>
        <?php
        $found=false;
        if($result->num_rows>0)
        {
            while($row=$result->fetch_assoc())
            { $show=true;
                if(isset($_POST["org_name"]) && $_POST["org_name"]!="")
                {
                    if(stripos($row["org_name"], $_POST["org_name"])===false)
                    {
                        $show=false;
                    }
                }
                if(isset($_POST["location"]) && $_POST["location"]!="")
                {
                    if(stripos($row["location"], $_POST["location"])===false)
                    {
                        $show=false;
                    }
                }
                if(isset($_POST["priority_level"]) && $_POST["priority_level"]!="")
                {
                    if(stripos($row["priority_level"], $_POST["priority_level"])===false)
                    {
                        $show=false;
                    }
                }
                if($show)
                {
                    $found=true;
        ?>
        <tr>
            <td><?php echo $row["recipient_id"]; ?></td>
            <td><?php echo $row["org_name"]; ?></td>
            <td><?php echo $row["location"]; ?></td>
            <td><?php echo $row["priority_level"]; ?></td>
            <td><?php echo $row["capacity"]; ?></td>
        </tr>
        <?php
                }
            }
        }
        if(!$found)
        {
            echo "<tr>
                    <td colspan='5'>
                    No matching recipient organizations found.
                    </td>
                  </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>