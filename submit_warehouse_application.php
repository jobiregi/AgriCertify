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
        $application_type = $_POST['application_type'];
        $postal_address = $_POST['postal_address'] ?? '';
        $postal_code = $_POST['postal_code'] ?? '';
        $registered_office = $_POST['registered_office'] ?? '';
        $building = $_POST['building'] ?? '';
        $street = $_POST['street'] ?? '';
        $city = $_POST['city'] ?? '';
        $lr_number = $_POST['lr_number'] ?? '';
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $incorporation_date = $_POST['incorporation_date'] ?? null;
        $registration_no = $_POST['registration_no'] ?? '';
        $county = $_POST['county'];
        $sub_county = $_POST['sub_county'];
        $ward = $_POST['ward'];
        
        // Directors
        $director_name_a = $_POST['director_name_a'] ?? '';
        $director_address_a = $_POST['director_address_a'] ?? '';
        $director_occupation_a = $_POST['director_occupation_a'] ?? '';
        $director_name_b = $_POST['director_name_b'] ?? '';
        $director_address_b = $_POST['director_address_b'] ?? '';
        $director_occupation_b = $_POST['director_occupation_b'] ?? '';
        $director_name_c = $_POST['director_name_c'] ?? '';
        $director_address_c = $_POST['director_address_c'] ?? '';
        $director_occupation_c = $_POST['director_occupation_c'] ?? '';
        
        // Declaration
        $authorized_officer = $_POST['authorized_officer'];
        $designation = $_POST['designation'];
        $signature = $_POST['signature'];
        $sign_date = $_POST['sign_date'];
        $stamp = $_POST['stamp'] ?? '';
        
        // Generate reference number
        $ref_number = 'WH-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO warehouse_applications (
            user_id, applicant_name, application_type, postal_address, postal_code,
            registered_office, building, street, city, lr_number, mobile, email,
            incorporation_date, registration_no, county, sub_county, ward,
            director_name_a, director_address_a, director_occupation_a,
            director_name_b, director_address_b, director_occupation_b,
            director_name_c, director_address_c, director_occupation_c,
            authorized_officer, designation, signature, sign_date, stamp, ref_number
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $userId, $applicant_name, $application_type, $postal_address, $postal_code,
            $registered_office, $building, $street, $city, $lr_number, $mobile, $email,
            $incorporation_date, $registration_no, $county, $sub_county, $ward,
            $director_name_a, $director_address_a, $director_occupation_a,
            $director_name_b, $director_address_b, $director_occupation_b,
            $director_name_c, $director_address_c, $director_occupation_c,
            $authorized_officer, $designation, $signature, $sign_date, $stamp, $ref_number
        ]);
        
        $applicationId = $conn->lastInsertId();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Warehouse application submitted successfully!',
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
?>