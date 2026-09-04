<?php
require_once "DBconnect.php";
$success=false;
$error="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $rating=$_POST["rating"];
    $comments=$_POST["comments"];
    $date=$_POST["date"];
    $distribution_id=$_POST["distribution_id"];
    $recipient_id=$_POST["recipient_id"];
    $sql="INSERT INTO Feedback
    (rating,comments,date,distribution_id,recipient_id)
    VALUES (?,?,?,?,?)";
    $stmt=$conn->prepare($sql);
    if($stmt){
        $stmt->bind_param(
            "issii",
            $rating,
            $comments,
            $date,
            $distribution_id,
            $recipient_id
        );
        if($stmt->execute()){
            $success=true;
        }else{
            $error="Feedback could not be added. Please try again.";
        }
        $stmt->close();
    }else{
        $error="Something went wrong while preparing the feedback.";
    }
}else{
    $error="No feedback information was submitted.";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Feedback Submission</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFeedback.php">Feedback</a>
<a href="showCompleted.php">Completed</a>
</div>
</nav>
<div class="success-page">
<div class="success-card">
<?php if($success){ ?>
<div class="success-icon">✓</div>
<h1>Feedback added successfully!</h1>
<p class="success-message">
Thank you for sharing your feedback.</p>
<div class="success-buttons">
<a href="showFeedback.php" class="btn">View Feedback</a>
<a href="addFeedback.php" class="btn-outline">Add Another Feedback</a>
</div>
<?php }else{ ?>
<div class="error-icon">!</div>
<h1>Feedback could not be added</h1>
<p class="error-message">
<?php echo htmlspecialchars($error); ?>
</p>
<div class="success-buttons">
<a href="addFeedback.php" class="btn">Try Again</a>
<a href="showFeedback.php" class="btn-outline">View Feedback</a>
</div>
<?php } ?>
</div>
</div>
</body>
</html>