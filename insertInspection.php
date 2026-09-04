<!DOCTYPE html>
<html>
<head>
<title>Inspection Added</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
<div class="logo">ZERO HUNGER</div>
<div class="nav-links">
<a href="index.php">Home</a>
<a href="showFoodItems.php">Food Items</a>
<a href="showInspections.php">Inspections</a>
</div>
</nav>
<div class="success-page">
<div class="success-card">
<?php if ($success){ ?>
<div class="success-icon">✓</div>
<h1>Inspection added successfully!</h1>
<p class="success-message">The food inspection details have been saved.</p>
<div class="success-buttons">
<a href="showInspections.php" class="btn">View Inspections</a>
<a href="addInspection.php" class="btn-outline">Add Another Inspection</a>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
<?php } ?>
</div>
</div>
</body>
</html>