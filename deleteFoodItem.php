<?php
require_once "DBconnect.php";
if(isset($_GET["id"])){
    $id=(int)$_GET["id"];
    $sql="DELETE FROM Food_item WHERE item_id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i",$id);
    if($stmt->execute()){
        header("Location: showFoodItems.php");
        exit();}
    else{
     echo "Error deleting food item: ".$conn->error;
    }}
$conn->close();
?>