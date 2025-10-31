<?php
session_start();

// Database connection
$host = "localhost";
$dbname = "agri_certify";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log error instead of displaying to user
    error_log("Database connection failed: " . $conn->connect_error);
    header("Location: login.html?error=invalid");
    exit();
}

// Initialize error variable
$error = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input values
    $email = trim($_POST['email'] ?? '');
    $passwordInput = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($email) || empty($passwordInput)) {
        $error = "Please fill in all fields.";
    } else {
        // Prepare and execute query
        $stmt = $conn->prepare("SELECT id, full_name, email, password FROM userdata WHERE email = ?");
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verify password
                if (password_verify($passwordInput, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['logged_in'] = true;

                    // Redirect to dashboard
                    header("Location: applicant_dashboard.php");
                    exit();
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "No account found with that email.";
            }

            $stmt->close();
        } else {
            $error = "Database error. Please try again.";
        }
    }
    
    // Close connection
    $conn->close();
    
    // If there was an error, redirect back to login page with error
    if ($error) {
        header("Location: login.html?error=invalid");
        exit();
    }
} else {
    // If not a POST request, redirect to login page
    header("Location: login.html");
    exit();
}
?>