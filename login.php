<?php
session_start();
require_once "DBconnect.php";

$error="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $username=$_POST["username"];
    $password=$_POST["password"];

    $sql="SELECT user_id,username,role
          FROM User
          WHERE username=? AND password=?";

    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ss",$username,$password);
    $stmt->execute();

    $result=$stmt->get_result();

    if($result->num_rows==1){

        $row=$result->fetch_assoc();

        $_SESSION["user_id"]=$row["user_id"];
        $_SESSION["username"]=$row["username"];
        $_SESSION["role"]=$row["role"];

        header("Location: index.php");
        exit();

    }else{
        $error="Invalid username or password.";
    }
}
?>