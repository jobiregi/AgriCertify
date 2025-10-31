<?php
// Allow CORS (for frontend on different domain or port)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Show errors for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Accept only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Only POST requests are allowed"]);
    exit;
}

// DB Connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "agri_certify";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch and sanitize filters (if any)
$town = $conn->real_escape_string($_POST['town_city'] ?? '');
$applicant = $conn->real_escape_string($_POST['applicant_name'] ?? '');

// Build dynamic SQL query
$sql = "SELECT * FROM coffee_roaster_applications WHERE 1=1";
if (!empty($town)) {
    $sql .= " AND town_city = '$town'";
}
if (!empty($applicant)) {
    $sql .= " AND applicant_name LIKE '%$applicant%'";
}

$result = $conn->query($sql);

$applications = [];
$total = 0;
$approved = 0;
$rejected = 0;

// Process result
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
        $total++;

        // Simulated status — replace with real status column when added
        $randStatus = rand(0, 2);
        if ($randStatus === 0) $approved++;
        elseif ($randStatus === 1) $rejected++;
    }
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Query failed: " . $conn->error]);
    exit;
}

// Respond with JSON
echo json_encode([
    "status" => "success",
    "total" => $total,
    "approved" => $approved,
    "rejected" => $rejected,
    "applications" => $applications
]);

$conn->close();
?>
