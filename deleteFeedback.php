<?php
require_once "DBconnect.php";
if(isset($_GET["id"])){
    $id=(int)$_GET["id"];
    $sql="DELETE FROM Feedback WHERE feedback_id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i",$id);
    if($stmt->execute()){
        header("Location: showFeedback.php");
        exit();
    }
    else{ echo "Error deleting feedback: ".$stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>