<?php
require_once "DBconnect.php";
if(isset($_GET["id"])){
    $id=(int)$_GET["id"];
    $sql="DELETE FROM Volunteer WHERE volunteer_id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i",$id);
    if($stmt->execute()){
        header("Location: showVolunteers.php");
        exit();
    }else{
        echo "Error deleting volunteer: ".$stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>