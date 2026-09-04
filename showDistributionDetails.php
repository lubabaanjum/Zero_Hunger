<?php
require_once "DBconnect.php";
$distribution_id="";
if(isset($_POST["distribution_id"]))
{
    $distribution_id=$_POST["distribution_id"];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Distribution Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">ZERO HUNGER</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showLifecycle.php">Lifecycle</a>
    </div>
</nav>
<div class="form-container">
    <h1 class="page-title">Distribution Details</h1>
    <form method="POST">
        <div class="form-group">
            <label>Distribution ID:</label>
            <input type="number"
                   name="distribution_id"
                   required>
        </div>
        <button type="submit" class="btn">
            View Details
        </button>
    </form>
</div>
<?php
if($distribution_id!="")
{
    $sql="SELECT ds.distribution_id, ds.pickup_time,ds.delivery_time,ds.status,a.allocation_id,a.quantity,d.donation_id,d.donation_date,
d.pickup_location,dr.name AS donor_name,dr.phone AS donor_phone,r.org_name,r.location AS recipient_location,r.priority_level,
m.mission_name, m.mission_date, m.status AS mission_status FROM Distribution ds JOIN Resource_Allocation a ON ds.allocation_id=a.allocation_id
 JOIN Food_donation d ON a.donation_id=d.donation_id JOIN Donor dr ON d.donor_id=dr.donor_id JOIN Recipient_Organization r ON a.recipient_id=r.recipient_id
 LEFT JOIN Rescue_Mission m ON ds.mission_id=m.mission_id WHERE ds.distribution_id='$distribution_id'";
    $result=$conn->query($sql);
    ?>
    <div class="container">
    <?php
    if($result->num_rows>0)
    {
        $row=$result->fetch_assoc();
    ?>
        <h2>Distribution Information</h2>
        <table>
            <tr>
                <th>Distribution ID</th>
                <td><?php echo $row["distribution_id"]; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $row["status"]; ?></td>
            </tr>
            <tr>
                <th>Pickup Time</th>
                <td><?php echo $row["pickup_time"]; ?></td>
            </tr>
            <tr>
                <th>Delivery Time</th>
                <td><?php echo $row["delivery_time"]; ?></td>
            </tr>
            <tr>
                <th>Donor</th>
                <td><?php echo $row["donor_name"]; ?></td>
            </tr>
            <tr>
                <th>Donor Phone</th>
                <td><?php echo $row["donor_phone"]; ?></td>
            </tr>
            <tr>
                <th>Donation ID</th>
                <td><?php echo $row["donation_id"]; ?></td>
            </tr>
            <tr>
                <th>Donation Date</th>
                <td><?php echo $row["donation_date"]; ?></td>
            </tr>
            <tr>
                <th>Pickup Location</th>
                <td><?php echo $row["pickup_location"]; ?></td>
            </tr>
            <tr>
                <th>Allocation ID</th>
                <td><?php echo $row["allocation_id"]; ?></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><?php echo $row["quantity"]; ?></td>
            </tr>
            <tr>
                <th>Recipient</th>
                <td><?php echo $row["org_name"]; ?></td>
            </tr>
            <tr>
                <th>Recipient Location</th>
                <td><?php echo $row["recipient_location"]; ?></td>
            </tr>
            <tr>
                <th>Priority Level</th>
                <td><?php echo $row["priority_level"]; ?></td>
            </tr>
            <tr>
                <th>Mission</th>
                <td><?php echo $row["mission_name"]; ?></td>
            </tr>
            <tr>
                <th>Mission Date</th>
                <td><?php echo $row["mission_date"]; ?></td>
            </tr>
            <tr>
                <th>Mission Status</th>
                <td><?php echo $row["mission_status"]; ?></td>
            </tr>
        </table>
    <?php
    }
    else
    {
        echo "<p>No distribution found with this ID.</p>";
    }
    ?>
    </div>
    <?php

}
$conn->close();
?>
</body>
</html>