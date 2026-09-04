<?php
require_once "DBconnect.php";
if(isset($_GET["id"])){
    $id=(int)$_GET["id"];
    $sql="DELETE FROM Volunteer_Assignment WHERE assignment_id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i",$id);
    if($stmt->execute()){
        header("Location: showAssignments.php");
        exit();
    }else{
        echo "Error deleting assignment: ".$stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>