<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

include 'db_connect.php';

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Only POST requests allowed"]);
    exit;
}

// Read input JSON
$data = json_decode(file_get_contents("php://input"), true);
$id     = $data['id'] ?? null;
$type   = $data['type'] ?? null;
$reason = $data['reason'] ?? 'Not specified';

// Map type to table
$tables = [
    "coffee_nursery" => "coffee_nursery_applications",
    "growers"        => "coffee_growers_applications",
    "milling"        => "milling_applications",
    "warehouse"      => "warehouse_applications",
    "roaster"        => "roaster_applications",
    "pulping"        => "pulping_station_applications",
    "grower_miller"  => "grower_miller_applications"
];

if (!$id || !$type || !isset($tables[$type])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing or invalid parameters"]);
    exit;
}

$table = $tables[$type];

// Reject
$stmt = $conn->prepare("UPDATE $table SET status = 'Rejected', rejection_reason = ? WHERE id = ?");
$stmt->bind_param("si", $reason, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Application rejected"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$stmt->close();
$conn->close();
