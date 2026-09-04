<?php

require_once "DBconnect.php";

$donation_sql = "SELECT donation_id FROM Food_Donation ORDER BY donation_id ASC";
$donation_result = $conn->query($donation_sql);

$recipient_sql = "SELECT recipient_id FROM Recipient_Organization ORDER BY recipient_id ASC";
$recipient_result = $conn->query($recipient_sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Resource Allocation</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

    <div class="logo">ZERO HUNGER</div>

    <div class="nav-links">

        <a href="index.php">Home</a>
        <a href="showMission.php">Rescue Missions</a>
        <a href="showPartners.php">Partners</a>
        <a href="showDistributions.php">Distributions</a>
        <a href="showAllocations.php">Allocations</a>
        <a href="showRecipientRequirements.php">Requirements</a>

    </div>

</nav>


<div class="form-container">

    <h1 class="page-title">Add Resource Allocation</h1>

    <form action="insertAllocation.php" method="POST">


        <!-- Quantity -->

        <div class="form-group">

            <label>Quantity:</label>

            <input type="number"
                   name="quantity"
                   min="1"
                   required>

        </div>


        <!-- Date -->

        <div class="form-group">

            <label>Date:</label>

            <input type="date"
                   name="date"
                   required>

        </div>


        <!-- Donation ID -->

        <div class="form-group">

            <label>Donation ID:</label>

            <select name="donation_id" required>

                <option value="">-- Select Donation --</option>

                <?php

                if ($donation_result->num_rows > 0) {

                    while ($donation = $donation_result->fetch_assoc()) {

                ?>

                    <option value="<?php echo $donation["donation_id"]; ?>">

                        Donation ID:
                        <?php echo $donation["donation_id"]; ?>

                    </option>

                <?php

                    }

                }
                else {

                    echo '<option value="">No donations available</option>';

                }

                ?>

            </select>

        </div>


        <!-- Recipient ID -->

        <div class="form-group">

            <label>Recipient ID:</label>

            <select name="recipient_id" required>

                <option value="">-- Select Recipient --</option>

                <?php

                if ($recipient_result->num_rows > 0) {

                    while ($recipient = $recipient_result->fetch_assoc()) {

                ?>

                    <option value="<?php echo $recipient["recipient_id"]; ?>">

                        Recipient ID:
                        <?php echo $recipient["recipient_id"]; ?>

                    </option>

                <?php

                    }

                }
                else {

                    echo '<option value="">No recipients available</option>';

                }

                ?>

            </select>

        </div>


        <button type="submit" class="btn">

            Add Allocation

        </button>

    </form>

</div>


</body>

</html>

<?php

$conn->close();

?>