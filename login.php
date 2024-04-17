<?php
session_start();
include 'db.php'; // Include database connection

// Check if email and password are set in $_POST
if (isset($_POST['email'], $_POST['password'])) {
    // Get user input   
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user from database
    $stmt = $mysqli->prepare("SELECT id, email, password FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row was returned
    if ($result->num_rows == 1) {
        // Fetch the user's data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Authentication successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_password'] = $user['password'];

            header("Location: student_details.php");
            exit();
        } else {
            // Invalid password
            $errorMessage = "Invalid email or password.";
        }
    } else {
        // No user found with the given email
        $errorMessage = "Invalid email or password.";
    }
} else {
    // Email or password not provided
    $errorMessage = "No email and password provided.";
}

// If authentication fails or no credentials provided, redirect back to login page with error message
echo "<script>alert('$errorMessage'); window.history.back();</script>";
exit();
?>
