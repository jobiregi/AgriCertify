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
$ref_number = $params['ref_number'] ?? '';
$grower_code = $params['grower_code'] ?? '';

// Sanitize inputs
$subcounty = $conn->real_escape_string($subcounty);
$ward = $conn->real_escape_string($ward);
$status = $conn->real_escape_string($status);
$dateFrom = $conn->real_escape_string($dateFrom);
$dateTo = $conn->real_escape_string($dateTo);
$ref_number = $conn->real_escape_string($ref_number);
$grower_code = $conn->real_escape_string($grower_code);

// Build query
$sql = "SELECT 
            id,
            grower_name,
            grower_code,
            category,
            other_category,
            county,
            sub_county AS subcounty,
            ward,
            mobile,
            email,
            status,
            ref_number,
            DATE(created_at) AS application_date,
            created_at,
            updated_at
        FROM grower_notifications 
        WHERE 1=1";

if (!empty($subcounty)) $sql .= " AND sub_county = '$subcounty'";
if (!empty($ward)) $sql .= " AND ward = '$ward'";
if (!empty($status)) $sql .= " AND status = '$status'";
if (!empty($dateFrom)) $sql .= " AND DATE(created_at) >= '$dateFrom'";
if (!empty($dateTo)) $sql .= " AND DATE(created_at) <= '$dateTo'";
if (!empty($ref_number)) $sql .= " AND ref_number = '$ref_number'";
if (!empty($grower_code)) $sql .= " AND grower_code = '$grower_code'";

$sql .= " ORDER BY created_at DESC";

$result = $conn->query($sql);

$notifications = [];
$total = 0;
$pending = 0;
$approved = 0;
$rejected = 0;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
        $total++;

        if (strcasecmp($row['status'], 'Pending') == 0) $pending++;
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
    "notifications" => $notifications,
    "stats" => [
        "total" => $total,
        "pending" => $pending,
        "approved" => $approved,
        "rejected" => $rejected
    ]
]);

$conn->close();
?>