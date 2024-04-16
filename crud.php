<?php
include 'inputvalidation.php';

// Establish connection to MySQL database
$mysqli = new mysqli('localhost', 'admin', 'admin', 'student');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle GET request for fetching all student
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all students from the database
    $result = $mysqli->query("SELECT * FROM students");
    $students = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }

    // Return students data in JSON format
    echo json_encode($students);
}

// Handle POST request for creating a Student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $matricno = $_POST['matricno'];
    $email = $_POST['email'];
    $curraddress = $_POST['curraddress'];
    $homeaddress = $_POST['homeaddress'];
    $mobilephone = $_POST['mobilephone'];
    $homephone = $_POST['homephone'];

    // Validate input
    $errors = validateInput($name, $matricno, $email, $curraddress, $homeaddress, $mobilephone, $homephone);

    if (empty($errors)) {
        // Prepare SQL statement
        $stmt = $mysqli->prepare("INSERT INTO students (name, matricno, email, curraddress, homeaddress, mobilephone, homephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssis", $name, $matricno, $email, $curraddress, $homeaddress, $mobilephone, $homephone);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Close statement
            $stmt->close();

            // Fetch all students and return them in JSON format
            $result = $mysqli->query("SELECT * FROM students");
            $students = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $students[] = $row;
                }
            }

            echo json_encode($students);


        } else {
            echo "Error: " . $mysqli->error;
        }
    } else {
        // Send error messages back to client-side JavaScript
        echo json_encode($errors);
    }
}

// Handle PUT request for updating a Student
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Retrieve form data
    parse_str(file_get_contents("php://input"), $putData);
    $id = $putData['id'];
    $name = $putData['name'];
    $matricno = $putData['matricno'];
    $email = $putData['email'];
    $curraddress = $putData['curraddress'];
    $homeaddress = $putData['homeaddress'];
    $mobilephone = $putData['mobilephone'];
    $homephone = $putData['homephone'];

    // Validate input
    $errors = validateInput($name, $matricno, $email, $curraddress, $homeaddress, $mobilephone, $homephone);

    if (empty($errors)) {
        // Prepare SQL statement
        $stmt = $mysqli->prepare("UPDATE students SET name=?, matricno=?, email=?, homephone=?, mobilephone=?, curraddres=?, homeaddress=? WHERE id=?");
        $stmt->bind_param("ssssssisi", $name, $matricno, $email, $curraddress, $homeaddress, $mobilephone, $homephone, $id);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Fetch all students and return them in JSON format after update
            $result = $mysqli->query("SELECT * FROM students");
            $students = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $students[] = $row;
                }
            }

            echo json_encode($students);
        } else {
            echo "Error: " . $mysqli->error;
        }
    } else {
        // Send error messages back to client-side JavaScript
        echo json_encode($errors);
    }
}


// Handle DELETE request for deleting a Student
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];

    // Prepare SQL statement
    $stmt = $mysqli->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Fetch all students and return them in JSON format after deletion
        $result = $mysqli->query("SELECT * FROM students");
        $students = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        echo json_encode($students);
    } else {
        echo "Error: " . $mysqli->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$mysqli->close();
?>
