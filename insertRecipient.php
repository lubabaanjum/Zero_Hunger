<?php

require_once "DBconnect.php";

$org_name=$_POST["org_name"];
$location=$_POST["location"];
$priority_level=$_POST["priority_level"];
$capacity=$_POST["capacity"];

$sql="INSERT INTO Recipient_Organization
      (org_name,location,priority_level,capacity)
      VALUES
      ('$org_name','$location','$priority_level','$capacity')";

if($conn->query($sql))
{
    header("Location: showRecipients.php");
   exit();
}
else
{
    echo "Error: " . $conn->error;
}

$conn->close();

?>
