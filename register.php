<?php
// register.php

// Database configuration
$host = "localhost";
$dbname = "agri_certify";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = isset($_POST['fullName']) ? sanitize($_POST['fullName']) : null;
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : null;
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validate required fields
    if (!$fullName || !$email || !$phone || !$password || !$confirmPassword) {
        die("Please fill in all required fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM userdata WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        $conn->close();
        die("An account with this email already exists.");
    }
    $check->close();

    // Insert user
    $stmt = $conn->prepare("INSERT INTO userdata (full_name, email, phone_number, password) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $fullName, $email, $phone, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if not POST
    header("Location: register.html");
    exit();
}
?>