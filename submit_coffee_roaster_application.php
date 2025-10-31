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
        $registered_address = $_POST['registered_address'];
        $building = $_POST['building'];
        $street = $_POST['street'];
        $town_city = $_POST['town_city'];
        $lr_no = $_POST['lr_no'];
        $mobile_no = $_POST['mobile_no'];
        $email = $_POST['email'];
        $company_name = $_POST['company_name'] ?? '';
        $date_incorporation = $_POST['date_incorporation'] ?? null;
        $registration_no = $_POST['registration_no'] ?? '';
        
        // Directors
        $director_name_1 = $_POST['director_name_1'] ?? '';
        $director_address_1 = $_POST['director_address_1'] ?? '';
        $director_occupation_1 = $_POST['director_occupation_1'] ?? '';
        $director_name_2 = $_POST['director_name_2'] ?? '';
        $director_address_2 = $_POST['director_address_2'] ?? '';
        $director_occupation_2 = $_POST['director_occupation_2'] ?? '';
        $director_name_3 = $_POST['director_name_3'] ?? '';
        $director_address_3 = $_POST['director_address_3'] ?? '';
        $director_occupation_3 = $_POST['director_occupation_3'] ?? '';
        
        // Branch
        $branch_postal = $_POST['branch_postal'] ?? '';
        $branch_postal_code = $_POST['branch_postal_code'] ?? '';
        $branch_building = $_POST['branch_building'] ?? '';
        $branch_street = $_POST['branch_street'] ?? '';
        $branch_city = $_POST['branch_city'] ?? '';
        $branch_lr_no = $_POST['branch_lr_no'] ?? '';
        $branch_mobile = $_POST['branch_mobile'] ?? '';
        $branch_email = $_POST['branch_email'] ?? '';
        
        // Declaration
        $declaration_date = $_POST['declaration_date'];
        $declaration_director_1 = $_POST['declaration_director_1'] ?? '';
        $signature_1 = $_POST['signature_1'] ?? '';
        $declaration_director_2 = $_POST['declaration_director_2'] ?? '';
        $signature_2 = $_POST['signature_2'] ?? '';
        $declaration_director_3 = $_POST['declaration_director_3'] ?? '';
        $signature_3 = $_POST['signature_3'] ?? '';
        
        // Authorized officer
        $authorized_name = $_POST['authorized_name'] ?? '';
        $authorized_address = $_POST['authorized_address'] ?? '';
        $authorized_mobile = $_POST['authorized_mobile'] ?? '';
        
        // Generate reference number
        $ref_number = 'CR-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO coffee_roaster_applications (
            user_id, applicant_name, postal_address, postal_code, registered_address, building, street,
            town_city, lr_no, mobile_no, email, company_name, date_incorporation, registration_no,
            director_name_1, director_address_1, director_occupation_1,
            director_name_2, director_address_2, director_occupation_2,
            director_name_3, director_address_3, director_occupation_3,
            branch_postal, branch_postal_code, branch_building, branch_street, branch_city,
            branch_lr_no, branch_mobile, branch_email, declaration_date,
            declaration_director_1, signature_1, declaration_director_2, signature_2,
            declaration_director_3, signature_3, authorized_name, authorized_address,
            authorized_mobile, ref_number
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $userId, $applicant_name, $postal_address, $postal_code, $registered_address, $building, $street,
            $town_city, $lr_no, $mobile_no, $email, $company_name, $date_incorporation, $registration_no,
            $director_name_1, $director_address_1, $director_occupation_1,
            $director_name_2, $director_address_2, $director_occupation_2,
            $director_name_3, $director_address_3, $director_occupation_3,
            $branch_postal, $branch_postal_code, $branch_building, $branch_street, $branch_city,
            $branch_lr_no, $branch_mobile, $branch_email, $declaration_date,
            $declaration_director_1, $signature_1, $declaration_director_2, $signature_2,
            $declaration_director_3, $signature_3, $authorized_name, $authorized_address,
            $authorized_mobile, $ref_number
        ]);
        
        $applicationId = $conn->lastInsertId();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Coffee Roaster application submitted successfully!',
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