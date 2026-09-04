<!DOCTYPE html>
<html>
<head>
<title>Add Food Category</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
<h1>Add Food Category</h1>
<form action="insertFoodCategory.php" method="POST">
<div class="form-group">
<label>Category Name:</label>
<input type="text" name="category_name" required>
</div>
<button type="submit" class="btn">Add Category</button>
</form>
<br><br>
<a href="javascript:history.back()" class="btn">Back</a>
</div>
</body>
</html>