<?php
require_once "DBconnect.php";
$item_id = $_POST["item_id"];
$inspection_date = $_POST["inspection_date"];
$quality_status = $_POST["quality_status"];
$remarks = $_POST["remarks"];
$sql = "INSERT INTO Food_Inspection (inspection_date, quality_status, remarks) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sss",
    $inspection_date,
    $quality_status,
    $remarks
);
if ($stmt->execute()) {
    $inspection_id = $stmt->insert_id;
    $sql2 = "INSERT INTO Inspects
             (item_id, inspection_id)
             VALUES (?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param(
        "ii",
        $item_id,
        $inspection_id
    );
    $stmt2->execute();
    echo "Inspection added successfully!";
    echo "<br><br>";
    echo "<a href='showInspections.php'>View Inspections</a>";
} 
else{
echo "Error: " . $stmt->error;}
$stmt->close();
$conn->close();
?>