<?php
require_once "DBconnect.php";
$rating = $_POST["rating"];
$comments = $_POST["comments"];
$date = $_POST["date"];
$distribution_id = $_POST["distribution_id"];
$recipient_id = $_POST["recipient_id"];
$sql = "INSERT INTO Feedback (rating, comments, date, distribution_id, recipient_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
     "issii", $rating,$comments,$date,$distribution_id,$recipient_id
);
if ($stmt->execute()) {
    echo "Feedback added successfully!";
    echo "<br><br>";
    echo "<a href='showFeedback.php'>View Feedback</a>";} 
else{
    echo "Error: " . $stmt->error;}
$stmt->close();
$conn->close();
?>