<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();
require_once "DBconnect.php";
$error="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username=$_POST["username"];
    $password=$_POST["password"];
    $role=$_POST["role"];
    $sql="SELECT user_id,username,role FROM User WHERE username=? AND password=? AND role=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("sss",$username,$password,$role);
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
        $error="Invalid username, password or role.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Zero Hunger Login</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="login-container">
<h1>Zero Hunger</h1>
<h2>Login</h2>
<?php if($error!=""){ ?>
<p class="login-error">
<?php echo $error; ?>
</p>
<?php } ?>
<form method="POST">
<label>Username</label>
<input type="text" name="username" required>
<label>Password</label>
<input type="password" name="password" required>
<label>Role</label>
<select name="role" required>
    <option value="">Select Role</option>
    <option value="Admin">Admin</option>
    <option value="Donor">Donor</option>
    <option value="Volunteer">Volunteer</option>
    <option value="Recipient">Recipient</option>
</select>
<button type="submit" class="btn">
Login
</button>
</form>
</div>
</body>
</html>