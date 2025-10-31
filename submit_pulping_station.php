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
        $postal_address = $_POST['postal_address'];
        $postal_code = $_POST['postal_code'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $county = $_POST['county'];
        $sub_county = $_POST['sub_county'];
        $ward = $_POST['ward'];
        $village_road = $_POST['village_road'];
        $nearest_institution = $_POST['nearest_institution'] ?? '';
        $farm_name = $_POST['farm_name'] ?? '';
        $lr_no = $_POST['lr_no'];
        
        // Certification
        $cert_name1 = $_POST['cert_name1'] ?? '';
        $cert_signature1 = $_POST['cert_signature1'] ?? '';
        $cert_date1 = $_POST['cert_date1'] ?? null;
        $cert_name2 = $_POST['cert_name2'] ?? '';
        $cert_signature2 = $_POST['cert_signature2'] ?? '';
        $cert_date2 = $_POST['cert_date2'] ?? null;
        $cert_name3 = $_POST['cert_name3'] ?? '';
        $cert_signature3 = $_POST['cert_signature3'] ?? '';
        $cert_date3 = $_POST['cert_date3'] ?? null;
        
        // Land details
        $acreage = $_POST['acreage'] ?? '';
        $number_of_trees = $_POST['number_of_trees'] ?? null;
        $variety = $_POST['variety'] ?? '';
        
        // Production
        $year1 = $_POST['year1'] ?? '';
        $production1 = $_POST['production1'] ?? null;
        $year2 = $_POST['year2'] ?? '';
        $production2 = $_POST['production2'] ?? null;
        $year3 = $_POST['year3'] ?? '';
        $production3 = $_POST['production3'] ?? null;
        
        // Recommendation
        $recommendation = $_POST['recommendation'] ?? '';
        
        // Officer details
        $officer_name = $_POST['officer_name'] ?? '';
        $designation = $_POST['designation'] ?? '';
        $officer_signature = $_POST['officer_signature'] ?? '';
        $officer_date = $_POST['officer_date'] ?? null;
        
        // Generate reference number
        $ref_number = 'PS-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO pulping_station_applications (
            user_id, applicant_name, postal_address, postal_code, email, telephone,
            county, sub_county, ward, village_road, nearest_institution, farm_name, lr_no,
            cert_name1, cert_signature1, cert_date1, cert_name2, cert_signature2, cert_date2,
            cert_name3, cert_signature3, cert_date3, acreage, number_of_trees, variety,
            year1, production1, year2, production2, year3, production3, recommendation,
            officer_name, designation, officer_signature, officer_date, ref_number
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $userId, $applicant_name, $postal_address, $postal_code, $email, $telephone,
            $county, $sub_county, $ward, $village_road, $nearest_institution, $farm_name, $lr_no,
            $cert_name1, $cert_signature1, $cert_date1, $cert_name2, $cert_signature2, $cert_date2,
            $cert_name3, $cert_signature3, $cert_date3, $acreage, $number_of_trees, $variety,
            $year1, $production1, $year2, $production2, $year3, $production3, $recommendation,
            $officer_name, $designation, $officer_signature, $officer_date, $ref_number
        ]);
        
        $applicationId = $conn->lastInsertId();
        
        // Handle file uploads
        $files = ['supporting_document', 'certificate_of_incorporation', 'list_of_directors'];
        foreach ($files as $fileField) {
            if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                handleFileUpload($_FILES[$fileField], $applicationId, 'pulping_station', $fileField, $userId);
            }
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Pulping Station application submitted successfully!',
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