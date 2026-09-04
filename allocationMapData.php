<?php

require_once "DBconnect.php";

/*
   This file supplies fresh allocation information to the map.
   The current database stores location as text rather than latitude/longitude.
   Therefore, recognized Bangladesh/Dhaka areas are mapped to fixed coordinates.
*/

$sql = "SELECT
            a.allocation_id,
            a.quantity,
            a.date,
            r.recipient_id,
            r.org_name,
            r.location,
            r.priority_level,
            d.distribution_id,
            d.status
        FROM Resource_Allocation a
        JOIN Recipient_Organization r
            ON a.recipient_id = r.recipient_id
        LEFT JOIN Distribution d
            ON a.allocation_id = d.allocation_id
        ORDER BY a.date DESC, a.allocation_id DESC";

$result = $conn->query($sql);

/*
   Coordinates are approximate area-centre points, not GPS positions.
   Add more areas here later if your database starts using new locations.
*/
$areaCoordinates = array(
    "Uttara" => array(23.8759, 90.3795),
    "Mirpur" => array(23.8223, 90.3654),
    "Dhanmondi" => array(23.7461, 90.3742),
    "Banani" => array(23.7937, 90.4066),
    "Mohakhali" => array(23.7772, 90.3991),
    "Gulshan" => array(23.7925, 90.4078),
    "Mohammadpur" => array(23.7679, 90.3587),
    "Jatrabari" => array(23.7104, 90.4358),
    "Motijheel" => array(23.7333, 90.4171),
    "Tejgaon" => array(23.7641, 90.4000),
    "Badda" => array(23.7806, 90.4254),
    "Rampura" => array(23.7636, 90.4206),
    "Farmgate" => array(23.7577, 90.3882),
    "Old Dhaka" => array(23.7104, 90.4074),
    "Dhaka" => array(23.8103, 90.4125),
    "Chattogram" => array(22.3569, 91.7832),
    "Chittagong" => array(22.3569, 91.7832),
    "Gazipur" => array(23.9999, 90.4203),
    "Narayanganj" => array(23.6238, 90.5000),
    "Savar" => array(23.8583, 90.2667),
    "Cumilla" => array(23.4607, 91.1809),
    "Rajshahi" => array(24.3745, 88.6042),
    "Khulna" => array(22.8456, 89.5403),
    "Sylhet" => array(24.8949, 91.8687),
    "Barishal" => array(22.7010, 90.3535),
    "Rangpur" => array(25.7439, 89.2752),
    "Mymensingh" => array(24.7471, 90.4203)
);

function findCoordinates($location, $areaCoordinates) {
    if ($location === null) {
        return array(null, null);
    }

    $locationText = trim($location);

    if ($locationText === "") {
        return array(null, null);
    }

    foreach ($areaCoordinates as $area => $coordinates) {
        if (stripos($locationText, $area) !== false) {
            return $coordinates;
        }
    }

    return array(null, null);
}

$locations = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {

        list($lat, $lng) = findCoordinates(
            $row["location"],
            $areaCoordinates
        );

        $locations[] = array(
            "allocation_id" => (int)$row["allocation_id"],
            "quantity" => (int)$row["quantity"],
            "date" => $row["date"],
            "recipient_id" => (int)$row["recipient_id"],
            "org_name" => $row["org_name"],
            "location" => $row["location"],
            "priority_level" => $row["priority_level"],
            "distribution_id" => $row["distribution_id"] === null ? null : (int)$row["distribution_id"],
            "status" => $row["status"],
            "lat" => $lat,
            "lng" => $lng
        );
    }
}

header("Content-Type: application/json");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

echo json_encode($locations);

$conn->close();
?>
