<?php
header("Content-Type: application/json");

// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "agri_certify";

// Connect to database
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

// Get optional filters from POST
$subcounty = $_POST['subcounty'] ?? '';
$ward = $_POST['ward'] ?? '';

$sql = "SELECT * FROM warehouse_applications WHERE 1=1";
$params = [];
$types = "";

// Add filters if set
if (!empty($subcounty)) {
    $sql .= " AND LOWER(subcounty) = LOWER(?)";
    $params[] = $subcounty;
    $types .= "s";
}

if (!empty($ward)) {
    $sql .= " AND LOWER(ward) = LOWER(?)";
    $params[] = $ward;
    $types .= "s";
}

// Prepare, bind, and execute
$stmt = $conn->prepare($sql);
if ($types !== "") {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

// Respond with data
echo json_encode([
    "total" => count($applications),
    "approved" => 0, // You can update this logic later
    "rejected" => 0, // You can update this logic later
    "applications" => $applications
]);

$stmt->close();
$conn->close();
?>
