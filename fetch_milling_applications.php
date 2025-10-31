<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// DB connection
$host = "localhost";
$user = "root";
$pass = ""; // Change if needed
$dbname = "agri_certify";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

// Receive filter inputs
$subcounty = $_POST['subcounty'] ?? '';
$ward = $_POST['ward'] ?? '';

// Prepare SQL with optional filters
$sql = "SELECT * FROM commercial_coffee_milling_applications WHERE 1";

if (!empty($subcounty)) {
    $sql .= " AND sub_county = '" . $conn->real_escape_string($subcounty) . "'";
}
if (!empty($ward)) {
    $sql .= " AND ward = '" . $conn->real_escape_string($ward) . "'";
}

$result = $conn->query($sql);

$applications = [];
$approved = 0;
$rejected = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // You can later track approval status if you add such a column
        $applications[] = $row;
    }
}

$response = [
    "total" => count($applications),
    "approved" => $approved,
    "rejected" => $rejected,
    "applications" => $applications
];

echo json_encode($response);
$conn->close();
?>
