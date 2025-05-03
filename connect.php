<?php
// DB connection
$host = "sql100.infinityfree.com";
$user = "if0_38415183";
$pass = "XugwJN6nlv";
$dbname = "if0_38415183_survey";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form data
$name = $_POST['name'];
$email = $_POST['email'];

$message = $_POST['message'];

// Insert into database
$sql = "INSERT INTO contact_data (name, email,  message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $message);

if ($stmt->execute()) {
    echo "Message sent successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
