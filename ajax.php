<?php
// -------------------------------
// ajax.php - Email Handler
// -------------------------------

if (isset($_GET['q']) && $_GET['q'] === "requestQuote") {

    // 1. Sanitize inputs
    $name    = trim($_POST['name'] ?? "");
    $email   = trim($_POST['email'] ?? "");
    $phone   = trim($_POST['phone'] ?? "");
    $subject = trim($_POST['subject'] ?? "New Enquiry");
    $message = trim($_POST['message'] ?? "");

    // 2. Server-side validation
    if ($name === "" || $email === "" || $phone === "" || $message === "") {
        echo "0"; exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "0"; exit;
    }

    // 3. Owner email
    $ownerEmail = "abhishektripathi0205@gmail.com";   // <--- Your email

    $emailSubject = "New Enquiry: " . $subject;

    $emailBody = "
        <h2>New Enquiry Received</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Message:</strong><br>{$message}</p>
    ";

    // 4. Email headers
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$name} <{$email}>\r\n";
    $headers .= "Reply-To: {$email}\r\n";

    // 5. Send email
    if (mail($ownerEmail, $emailSubject, $emailBody, $headers)) {
        echo "1";
    } else {
        echo "0";
    }

    exit;
}
?>
