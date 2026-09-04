<?php

require_once "DBconnect.php";

$food_needed=$_POST["food_needed"];
$urgency_level=$_POST["urgency_level"];
$quantity=$_POST["quantity"];
$recipient_id=$_POST["recipient_id"];

$sql="INSERT INTO Recipient_requirement
      (food_needed,urgency_level,quantity,recipient_id)
      VALUES
      ('$food_needed','$urgency_level','$quantity','$recipient_id')";

if($conn->query($sql))
{
    echo "Recipient requirement added successfully!";
    echo "<br><br>";
    echo "<a href='showRecipientRequirements.php'>
          View Recipient Requirements</a>";
}
else
{
    echo "Error: " . $conn->error;
}

$conn->close();

?>