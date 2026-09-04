<?php 
require_once "DBconnect.php"; 
$sql = "SELECT d.distribution_id,  d.mission_id,  m.mission_name, d.pickup_time, d.status, d.delivery_time, d.allocation_id 
FROM Distribution d JOIN Rescue_Mission m ON d.mission_id = m.mission_id"; 
$result = $conn->query($sql); 
?> 
<!DOCTYPE html> 
<html> 
<head> 
    <title>Distributions</title> 
    <link rel="stylesheet" href="css/style.css"> 
</head> 
<body> 
<nav class="navbar"> 
    <div class="logo">ZERO HUNGER</div> 
    <div class="nav-links"> 
        <a href="index.php">Home</a> 
        <a href="distributionMenu.php">Distribution Menu</a> 
     </div> 
</nav> 
<div class="container"> 
   <h1 class="page-title">Distribution Records</h1> 
   <table> 
        <tr> 
            <th>Distribution ID</th> 
            <th>Mission ID</th> 
            <th>Mission Name</th> 
            <th>Allocation ID</th> 
            <th>Pickup Time</th> 
            <th>Delivery Time</th> 
            <th>Status</th>
            <th>Progress</th>
        </tr> 
      <?php 
        if ($result->num_rows > 0) { 
             while ($row = $result->fetch_assoc()) { 
               $status = $row["status"];
                $progress = 20;
                if ($status == "Picked Up") {
                    $progress = 48;
                }
                elseif ($status == "In Transit") {
                    $progress = 68;
                }
                elseif ($status == "Delivered") {
                    $progress = 100;
                }
        ?> 
        <tr> 
            <td><?php echo $row["distribution_id"]; ?></td> 
            <td><?php echo $row["mission_id"]; ?></td> 
            <td><?php echo $row["mission_name"]; ?></td> 
            <td><?php echo $row["allocation_id"]; ?></td> 
            <td><?php echo $row["pickup_time"]; ?></td> 
            <td><?php echo $row["delivery_time"]; ?></td> 
            <td><?php echo $row["status"]; ?></td>
            <td>
                <div class="progress-container">
                    <div class="progress-bar"
                         style="width: <?php echo $progress; ?>%;">
                        <?php echo $progress; ?>%
                    </div>
                </div>
            </td>
         </tr>  
       <?php 
            } 
        } 
        else { 
            echo "<tr> 
                    <td colspan='8'>No distribution records found.</td> 
                  </tr>"; 
         } 
        ?> 
    </table> 
</div> 
</body> 
</html>