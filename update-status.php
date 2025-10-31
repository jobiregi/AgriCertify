<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$appId = $input['id'];
$status = $input['status'];

// Update application status in database
// Return success/failure response