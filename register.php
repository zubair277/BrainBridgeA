<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection
include 'connect.php';

$response = ['success' => false, 'message' => ''];

// Get the form data
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Validate input data
if (empty($username) || empty($email) || empty($password)) {
    $response['message'] = 'All fields are required.';
    echo json_encode($response);
    exit;
}

// Check if the username or email already exists
$checkStmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$checkStmt->bind_param("ss", $username, $email);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    $response['message'] = 'Username or email already exists.';
    echo json_encode($response);
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute the insert statement
$insertStmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$insertStmt->bind_param("sss", $username, $email, $hashedPassword);

if ($insertStmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Registration successful! Redirecting...';
} else {
    $response['message'] = 'Registration failed. ' . $insertStmt->error; // Show the specific error
}

// Close the statements and connection
$insertStmt->close();
$checkStmt->close();
$conn->close();

// Return the response as JSON
echo json_encode($response);
?>
