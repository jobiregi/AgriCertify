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
        $grower_name = $_POST['grower_name'];
        $grower_code = $_POST['grower_code'];
        $category = $_POST['category'];
        $other_category = $_POST['other_category'] ?? '';
        $county = $_POST['county'];
        $sub_county = $_POST['sub_county'];
        $ward = $_POST['ward'];
        $village_road = $_POST['village_road'] ?? '';
        $postal_address = $_POST['postal_address'] ?? '';
        $email = $_POST['email'] ?? '';
        $mobile = $_POST['mobile'];
        $acreage_details = $_POST['acreage_details'] ?? '';
        $contract1 = $_POST['contract1'] ?? '';
        $contract2 = $_POST['contract2'] ?? '';
        $contract3 = $_POST['contract3'] ?? '';
        $liability1 = $_POST['liability1'] ?? '';
        $liability2 = $_POST['liability2'] ?? '';
        $liability3 = $_POST['liability3'] ?? '';
        $prepared_by = $_POST['prepared_by'];
        $designation = $_POST['designation'];
        $signature = $_POST['signature'];
        $notification_date = $_POST['date'];
        $stamp = $_POST['stamp'] ?? '';
        
        // Generate reference number
        $ref_number = 'GN-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO grower_notifications (
            user_id, grower_name, grower_code, category, other_category, county, sub_county, ward,
            village_road, postal_address, email, mobile, acreage_details,
            contract1, contract2, contract3, liability1, liability2, liability3,
            prepared_by, designation, signature, notification_date, stamp, ref_number
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $userId, $grower_name, $grower_code, $category, $other_category, $county, $sub_county, $ward,
            $village_road, $postal_address, $email, $mobile, $acreage_details,
            $contract1, $contract2, $contract3, $liability1, $liability2, $liability3,
            $prepared_by, $designation, $signature, $notification_date, $stamp, $ref_number
        ]);
        
        $notificationId = $conn->lastInsertId();
        
        // Handle file uploads
        if (isset($_FILES['acreage_documents']) && $_FILES['acreage_documents']['error'] === UPLOAD_ERR_OK) {
            handleFileUpload($_FILES['acreage_documents'], $notificationId, 'grower_notification', 'acreage_documents', $userId);
        }
        
        if (isset($_FILES['contract_documents']) && $_FILES['contract_documents']['error'] === UPLOAD_ERR_OK) {
            handleFileUpload($_FILES['contract_documents'], $notificationId, 'grower_notification', 'contract_documents', $userId);
        }
        
        if (isset($_FILES['liability_documents']) && $_FILES['liability_documents']['error'] === UPLOAD_ERR_OK) {
            handleFileUpload($_FILES['liability_documents'], $notificationId, 'grower_notification', 'liability_documents', $userId);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Notification submitted successfully!',
            'ref_number' => $ref_number,
            'notification_id' => $notificationId
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