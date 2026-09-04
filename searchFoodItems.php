<?php
require_once "DBconnect.php";
$search = "";
if (isset($_GET["search"])) {
    $search = $_GET["search"];
}
$search = $conn->real_escape_string($search);
$sql = "SELECT f.item_id,f.item_name, f.expiry_date, f.quantity, f.shelf_life, dr.name AS donor_name, dr.location AS donor_location FROM Food_Item f JOIN Food_Donation d ON f.donation_id = d.donation_id
 JOIN Donor dr ON d.donor_id = dr.donor_id WHERE f.item_name LIKE '%$search%' OR dr.location LIKE '%$search%' ORDER BY f.item_name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Find Food</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">
        ZERO HUNGER
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="showDonors.php">Donors</a>
        <a href="showRecipients.php">Recipients</a>
        <a href="showFoodItems.php">Food</a>
        <a href="showVolunteers.php">Volunteers</a>
        <a href="searchFoodItems.php">Find Food</a>
    </div>
</nav>
<div class="container">
    <h1 class="page-title">
        Find Food
    </h1>
    <p>
        Enter a food item or location to find available food and its donor information.
    </p>
    <form method="GET">
        <div class="form-group">
            <input
                type="text"
                name="search"
                value="<?php echo htmlspecialchars($search); ?>"
                placeholder="Enter food item or location"
                autofocus
            >
        </div>
    </form>
    <br>
    <?php
    if ($search != "") {
    ?>
    <table>
        <tr>
            <th>Food ID</th>
            <th>Food Name</th>
            <th>Expiry Date</th>
            <th>Quantity</th>
            <th>Shelf Life</th>
            <th>Donor Name</th>
            <th>Donor Location</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td>
                <?php echo $row["item_id"]; ?>
            </td>
            <td>
                <?php
                echo htmlspecialchars($row["item_name"]);
                ?>
            </td>
            <td>
                <?php echo $row["expiry_date"]; ?>
            </td>
            <td>
                <?php echo $row["quantity"]; ?>
            </td>
            <td>
                <?php echo $row["shelf_life"]; ?>
            </td>
            <td>
                <?php
                echo htmlspecialchars($row["donor_name"]);
                ?>
            </td>
            <td>
                <?php
                echo htmlspecialchars($row["donor_location"]);
                ?>
            </td>
        </tr>
        <?php
            }
        } else {
            echo "<tr>
                    <td colspan='7'>
                        No food found for this item or location.
                    </td>
                  </tr>";
        }
        ?>
    </table>
    <?php
    }
    ?>
</div>
</body>
</html>
<?php
$conn->close();
?>