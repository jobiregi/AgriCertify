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
        $postal_address = $_POST['postal_address'] ?? '';
        $postal_code = $_POST['postal_code'] ?? '';
        $email = $_POST['email'] ?? '';
        $mobile = $_POST['mobile'];
        $county = $_POST['county'];
        $sub_county = $_POST['sub_county'];
        $ward = $_POST['ward'];
        $village_road = $_POST['village_road'] ?? '';
        $plot = $_POST['plot'] ?? '';
        
        // Signatories
        $signatory_name_1 = $_POST['signatory_name_1'] ?? '';
        $signature_1 = $_POST['signature_1'] ?? '';
        $date_1 = $_POST['date_1'] ?? null;
        $signatory_name_2 = $_POST['signatory_name_2'] ?? '';
        $signature_2 = $_POST['signature_2'] ?? '';
        $date_2 = $_POST['date_2'] ?? null;
        $signatory_name_3 = $_POST['signatory_name_3'] ?? '';
        $signature_3 = $_POST['signature_3'] ?? '';
        $date_3 = $_POST['date_3'] ?? null;
        
        // Capacity
        $parchment = $_POST['parchment'] ?? null;
        $buni = $_POST['buni'] ?? null;
        $mill_certification = $_POST['mill_certification'] ?? '';
        
        // Final declaration
        $final_signature = $_POST['final_signature'];
        $final_date = $_POST['final_date'];
        
        // Generate reference number
        $ref_number = 'CM-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO commercial_milling_applications (
            user_id, applicant_name, nature, postal_address, postal_code, email, mobile,
            county, sub_county, ward, village_road, plot,
            signatory_name_1, signature_1, date_1,
            signatory_name_2, signature_2, date_2,
            signatory_name_3, signature_3, date_3,
            parchment, buni, mill_certification, final_signature, final_date, ref_number
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $userId, $applicant_name, $nature, $postal_address, $postal_code, $email, $mobile,
            $county, $sub_county, $ward, $village_road, $plot,
            $signatory_name_1, $signature_1, $date_1,
            $signatory_name_2, $signature_2, $date_2,
            $signatory_name_3, $signature_3, $date_3,
            $parchment, $buni, $mill_certification, $final_signature, $final_date, $ref_number
        ]);
        
        $applicationId = $conn->lastInsertId();
        
        // Handle file uploads
        $files = ['certificate', 'directors_list', 'insurance_policy', 'stamp'];
        foreach ($files as $fileField) {
            if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                handleFileUpload($_FILES[$fileField], $applicationId, 'commercial_milling', $fileField, $userId);
            }
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Commercial Milling application submitted successfully!',
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