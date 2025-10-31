<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Please login first']));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $userId = $_SESSION['user_id'];
        
        // Get form data
        $applicant_name = $_POST['applicant_name'];
        $nature = $_POST['nature'];
        $postal_address = $_POST['postal_address'];
        $postal_code = $_POST['postal_code'];
        $email = $_POST['email'];
        $mobile_number = $_POST['mobile_number'];
        $county = $_POST['county'];
        $sub_county = $_POST['sub_county'];
        $ward = $_POST['ward'];
        $village_road = $_POST['village_road'];
        $plot_no = $_POST['plot_no'];
        
        // Signatories
        $signatory1_name = $_POST['signatory1_name'] ?? '';
        $signatory2_name = $_POST['signatory2_name'] ?? '';
        
        // Capacity
        $parchment_capacity = $_POST['parchment_capacity'] ?? null;
        $buni_capacity = $_POST['buni_capacity'] ?? null;
        $mill_certification = $_POST['mill_certification'] ?? '';
        
        // Purpose
        $purpose_milling = isset($_POST['purpose']) && in_array('milling', $_POST['purpose']) ? 1 : 0;
        $purpose_marketing = isset($_POST['purpose']) && in_array('marketing', $_POST['purpose']) ? 1 : 0;
        $purpose_roasting = isset($_POST['purpose']) && in_array('roasting', $_POST['purpose']) ? 1 : 0;
        
        // Final declaration
        $final_date = $_POST['final_date'];
        
        // Generate reference number
        $ref_number = 'GM-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO grower_miller_applications (
            user_id, applicant_name, nature, postal_address, postal_code, email, mobile_number,
            county, sub_county, ward, village_road, plot_no, signatory1_name, signatory2_name,
            parchment_capacity, buni_capacity, mill_certification,
            purpose_milling, purpose_marketing, purpose_roasting, final_date, ref_number
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $userId, $applicant_name, $nature, $postal_address, $postal_code, $email, $mobile_number,
            $county, $sub_county, $ward, $village_road, $plot_no, $signatory1_name, $signatory2_name,
            $parchment_capacity, $buni_capacity, $mill_certification,
            $purpose_milling, $purpose_marketing, $purpose_roasting, $final_date, $ref_number
        ]);
        
        $applicationId = $conn->lastInsertId();
        
        // Handle file uploads
        $files = [
            'certificate', 'directors_list', 'signatory1_signature', 
            'signatory2_signature', 'applicant_signature', 'applicant_stamp'
        ];
        
        foreach ($files as $fileField) {
            if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                handleFileUpload($_FILES[$fileField], $applicationId, 'grower_miller', $fileField, $userId);
            }
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Grower Miller application submitted successfully!',
            'ref_number' => $ref_number,
            'application_id' => $applicationId
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request method'
    ]);
}

function handleFileUpload($file, $applicationId, $applicationType, $documentType, $userId) {
    global $conn;
    
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = $userId . '_' . time() . '_' . basename($file['name']);
        $targetPath = 'uploads/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $stmt = $conn->prepare("INSERT INTO application_documents (application_id, application_type, document_name, file_path) VALUES (?, ?, ?, ?)");
            $stmt->execute([$applicationId, $applicationType, $documentType, $filename]);
        }
    }
}
?>