<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_certify";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Create uploads directory if it doesn't exist
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $applicant_name = $conn->real_escape_string($_POST['applicant_name']);
        $nature_of_application = $conn->real_escape_string($_POST['nature_of_application']);
        $county = $conn->real_escape_string($_POST['county']);
        $sub_county = $conn->real_escape_string($_POST['sub_county']);
        $ward = $conn->real_escape_string($_POST['ward']);
        $village_road = $conn->real_escape_string($_POST['village_road'] ?? '');
        $nearest_public_institution = $conn->real_escape_string($_POST['nearest_public_institution'] ?? '');
        $land_registration_plot_no = $conn->real_escape_string($_POST['land_registration_plot_no'] ?? '');
        $postal_address = $conn->real_escape_string($_POST['postal_address'] ?? '');
        $email = $conn->real_escape_string($_POST['email'] ?? '');
        $telephone = $conn->real_escape_string($_POST['telephone']);
        $nursery_category = $conn->real_escape_string($_POST['nursery_category']);
        $application_date = $conn->real_escape_string($_POST['application_date']);
        $signature = $conn->real_escape_string($_POST['signature']);
        
        // Handle file uploads
        $national_id_filename = '';
        $land_document_filename = '';
        $certificate_of_incorporation_filename = '';
        $directors_list_filename = '';
        
        // Upload National ID (required)
        if (isset($_FILES['national_id_passport']) && $_FILES['national_id_passport']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['national_id_passport'];
            $filename = uniqid() . '_' . basename($file['name']);
            $targetPath = 'uploads/' . $filename;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $national_id_filename = $filename;
            } else {
                throw new Exception('Failed to upload National ID');
            }
        } else {
            throw new Exception('National ID/Passport is required');
        }
        
        // Upload Land Document (optional)
        if (isset($_FILES['land_document']) && $_FILES['land_document']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['land_document'];
            $filename = uniqid() . '_' . basename($file['name']);
            $targetPath = 'uploads/' . $filename;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $land_document_filename = $filename;
            }
        }
        
        // Upload Company Documents (optional)
        if (isset($_FILES['certificate_of_incorporation']) && $_FILES['certificate_of_incorporation']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['certificate_of_incorporation'];
            $filename = uniqid() . '_' . basename($file['name']);
            $targetPath = 'uploads/' . $filename;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $certificate_of_incorporation_filename = $filename;
            }
        }
        
        if (isset($_FILES['directors_list']) && $_FILES['directors_list']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['directors_list'];
            $filename = uniqid() . '_' . basename($file['name']);
            $targetPath = 'uploads/' . $filename;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $directors_list_filename = $filename;
            }
        }
        
        // Combine company documents
        $company_documents = [];
        if (!empty($certificate_of_incorporation_filename)) $company_documents[] = $certificate_of_incorporation_filename;
        if (!empty($directors_list_filename)) $company_documents[] = $directors_list_filename;
        $company_documents_str = implode(',', $company_documents);
        
        // Generate reference number
        $ref_number = 'CN-' . date('Ymd-His') . '-' . rand(1000, 9999);
        
        // SIMPLE INSERT - Let the database handle missing columns
        $sql = "INSERT INTO coffee_nursery_applications (
            applicant_name, national_id_passport, nature_of_application, county, sub_county, ward, 
            village_road, nearest_public_institution, land_registration_plot_no, land_document, 
            postal_address, email, telephone, company_documents, nursery_category, application_date, signature
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param(
                "sssssssssssssssss", 
                $applicant_name, 
                $national_id_filename, 
                $nature_of_application, 
                $county, 
                $sub_county, 
                $ward, 
                $village_road, 
                $nearest_public_institution, 
                $land_registration_plot_no, 
                $land_document_filename, 
                $postal_address, 
                $email, 
                $telephone, 
                $company_documents_str, 
                $nursery_category, 
                $application_date, 
                $signature
            );
            
            if ($stmt->execute()) {
                $application_id = $stmt->insert_id;
                
                $response = [
                    'success' => true, 
                    'message' => 'Application submitted successfully!', 
                    'ref_number' => $ref_number,
                    'application_id' => $application_id
                ];
                
                echo json_encode($response);
            } else {
                throw new Exception('Database execute error: ' . $stmt->error);
            }
            
            $stmt->close();
        } else {
            throw new Exception('Database prepare error: ' . $conn->error);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>