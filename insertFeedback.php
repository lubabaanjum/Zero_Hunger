<!DOCTYPE html>
<html>
<head>
<title>Feedback Added</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFeedback.php">Feedback</a>
<a href="showCompleted.php">Completed</a></div>
</nav>
<div class="success-page">
<div class="success-card">
<?php if ($success){?>
<div class="success-icon">✓</div>
<h1>Feedback added successfully!</h1>
<p class="success-message">Thank you for sharing your feedback.</p>
<div class="success-buttons">
<a href="showFeedback.php" class="btn">View Feedback</a>
<a href="addFeedback.php" class="btn-outline">Add Another Feedback</a>
</div>
<?php } ?>
</div>
</div>
</body>
</html>