<?php
header("Content-Type: application/json");

// DB config
$host = "localhost";
$username = "root";
$password = "";
$dbname = "agri_certify";

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Get POST data safely
$subcounty = isset($_POST['subcounty']) ? trim($_POST['subcounty']) : '';
$ward = isset($_POST['ward']) ? trim($_POST['ward']) : '';

// Debugging (can be removed in production)
error_log("Subcounty received: " . $subcounty);
error_log("Ward received: " . $ward);

// Base query
$sql = "SELECT * FROM pulping_station_applications WHERE 1=1";
$params = [];
$types = "";

// Add filters only if not empty
if (!empty($subcounty)) {
    $sql .= " AND LOWER(sub_county) = LOWER(?)";
    $params[] = $subcounty;
    $types .= "s";
}

if (!empty($ward)) {
    $sql .= " AND LOWER(ward) = LOWER(?)";
    $params[] = $ward;
    $types .= "s";
}

// Prepare and execute
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Query preparation failed: " . $conn->error]);
    exit;
}

// Bind parameters
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch data
$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

// Response
echo json_encode([
    "total" => count($applications),
    "approved" => 0,
    "rejected" => 0,
    "applications" => $applications
]);

// Cleanup
$stmt->close();
$conn->close();
?>
