<?php
// check_username.php
require_once 'connection.php';

header('Content-Type: application/json');

if (isset($_GET['username'])) {
    $username = sanitize_input($_GET['username'], $conn);
    
    // Check in department_users table
    $stmt = $conn->prepare("SELECT username FROM department_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo json_encode(['available' => false]);
    } else {
        // Also check in pending requests
        $stmt2 = $conn->prepare("SELECT username FROM department_registration_requests WHERE username = ? AND status = 'pending'");
        $stmt2->bind_param("s", $username);
        $stmt2->execute();
        $stmt2->store_result();
        
        if ($stmt2->num_rows > 0) {
            echo json_encode(['available' => false]);
        } else {
            echo json_encode(['available' => true]);
        }
        $stmt2->close();
    }
    
    $stmt->close();
} else {
    echo json_encode(['available' => false, 'error' => 'No username provided']);
}

$conn->close();
?>