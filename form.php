<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = []; // Array to store error messages

    // Validate and sanitize form inputs
    $name = validateInput($_POST["name"], '~^[A-Za-z ]+$~', 'Invalid name');
    $matricno = validateInput($_POST["matricno"], '~^\d{7}$~', 'Invalid matric number');
    $email = validateInput($_POST["email"], '~^[a-zA-Z0-9._%+-]+@gmail+\.[a-zA-Z]{2,}$~', 'Invalid email address');
    $curraddress = validateInput($_POST["curraddress"], '~^[A-Za-z0-9/\s,\-.]+$~', 'Invalid current address');
    $homeaddress = validateInput($_POST["homeaddress"], '~^[A-Za-z0-9/\s,\-.]+$~', 'Invalid home address');
    $mobilephone = validateInput($_POST["mobilephone"], '~^\d{3}-\d{3}-\d{4}$~', 'Invalid mobile phone number');
    $homephone = validateInput($_POST["homephone"], '~^\d{3}-\d{3}-\d{4}$~', 'Invalid home phone number');

    // If there are no errors, save the data to the database
    if (empty($errors)) {
        // Prepare a SQL statement to insert data into the database
        $stmt = $mysqli->prepare("INSERT INTO students (name, matricno, email, curraddress, homeaddress, mobilephone, homephone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        // Bind parameters to the prepared statement
        $stmt->bind_param("sssssss", $name, $matricno, $email, $curraddress, $homeaddress, $mobilephone, $homephone);
        
        // Execute the prepared statement
        if ($stmt->execute()) {
            $inserted_id = $mysqli->insert_id;

            // Redirect to student_details.html with the ID parameter
            header("Location: student_details.html?id=$inserted_id");

            exit();
        } else {
            // Error inserting data into the database
            echo "Error: " . $mysqli->error;
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // Display error messages
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }

        echo "<p style='color: red; font-size: 20px;'>Please go back and try again!</p>";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $response = array();

    // Retrieve the ID from the query parameters
    $id = $_GET['id'];

    // Prepare a SQL statement to fetch data for the specified ID
    $stmt = $mysqli->prepare("SELECT * FROM students WHERE id = ?");
    
    // Bind the ID parameter to the prepared statement
    $stmt->bind_param("i", $id);
    
    // Execute the prepared statement
    $stmt->execute();
    
    // Get the result set
    $result = $stmt->get_result();

    // Check if there are rows returned
    if ($result->num_rows > 0) {
        // Fetch data and store it in response array
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    }

    // Close the statement
    $stmt->close();

    // Set response header to indicate JSON content
    header('Content-Type: application/json');

    // Convert response array to JSON format and echo it
    echo json_encode($response);
} else {
    echo "<p>No form submission detected.</p>";
}

// Function to validate input using preg_match
function validateInput($input, $pattern, $error_message) {
    $validated_input = htmlspecialchars($input); // Sanitize input
    if (preg_match($pattern, $validated_input)) {
        return $validated_input;
    } else {
        global $errors; // Access global errors array
        $errors[] = $error_message; // Add error message to errors array
        return " "; // Return empty string for invalid input
    }
}
?>
