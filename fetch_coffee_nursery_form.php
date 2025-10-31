<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include 'db_connect.php';

// Accept both GET & POST for flexibility
$params = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

$subcounty = $params['subcounty'] ?? '';
$ward = $params['ward'] ?? '';
$status = $params['status'] ?? '';
$dateFrom = $params['dateFrom'] ?? '';
$dateTo = $params['dateTo'] ?? '';

// Sanitize
$subcounty = $conn->real_escape_string($subcounty);
$ward = $conn->real_escape_string($ward);
$status = $conn->real_escape_string($status);
$dateFrom = $conn->real_escape_string($dateFrom);
$dateTo = $conn->real_escape_string($dateTo);

// Build query
$sql = "SELECT 
            id,
            applicant_name,
            sub_county AS subcounty,
            ward,
            status,
            certification_type,
            DATE(created_at) AS application_date,
            ref_number
        FROM coffee_nursery_applications 
        WHERE 1=1";

if (!empty($subcounty)) $sql .= " AND sub_county = '$subcounty'";
if (!empty($ward)) $sql .= " AND ward = '$ward'";
if (!empty($status)) $sql .= " AND status = '$status'";
if (!empty($dateFrom)) $sql .= " AND DATE(created_at) >= '$dateFrom'";
if (!empty($dateTo)) $sql .= " AND DATE(created_at) <= '$dateTo'";

$sql .= " ORDER BY created_at DESC";

$result = $conn->query($sql);

$applications = [];
$total = 0;
$approved = 0;
$rejected = 0;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
        $total++;

        if (strcasecmp($row['status'], 'Approved') == 0) $approved++;
        if (strcasecmp($row['status'], 'Rejected') == 0) $rejected++;
    }
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Query failed: " . $conn->error]);
    exit;
}

echo json_encode([
    "status" => "success",
    "applications" => $applications,
    "stats" => [
        "total" => $total,
        "approved" => $approved,
        "rejected" => $rejected
    ]
]);

$conn->close();
