<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// DB connection setup
$host = "localhost";
$user = "root";
$pass = ""; // Update if needed
$dbname = "agri_certify";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Get POST data safely
$subcounty = trim($_POST['subcounty'] ?? '');
$ward = trim($_POST['ward'] ?? '');

// Prepare query
$sql = "SELECT * FROM grow_millers_licence WHERE 1";

if (!empty($subcounty)) {
    $sql .= " AND LOWER(sub_county) = LOWER('" . $conn->real_escape_string($subcounty) . "')";
}

if (!empty($ward)) {
    $sql .= " AND LOWER(ward) = LOWER('" . $conn->real_escape_string($ward) . "')";
}

$result = $conn->query($sql);

$applications = [];
$approved = 0;
$rejected = 0;

// Assuming you will later add approval status tracking
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

$response = [
    "total" => count($applications),
    "approved" => $approved,    // Will work once approval tracking added
    "rejected" => $rejected,
    "applications" => $applications
];

echo json_encode($response);
$conn->close();
?>
