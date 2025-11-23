<?php
// -------------------------------
// ajax.php
// -------------------------------

if (isset($_GET['q']) && $_GET['q'] === "requestQuote") {

    // ---------------------------------
    // 1. Collect and sanitize inputs
    // ---------------------------------
    $name    = isset($_POST['name']) ? trim($_POST['name']) : "";
    $email   = isset($_POST['email']) ? trim($_POST['email']) : "";
    $phone   = isset($_POST['phone']) ? trim($_POST['phone']) : "";
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : "New Enquiry";
    $message = isset($_POST['message']) ? trim($_POST['message']) : "";

    // ---------------------------------
    // 2. Server-side validation
    // ---------------------------------
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        echo "0";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "0";
        exit;
    }

    // ---------------------------------
    // 3. Prepare email to the owner
    // ---------------------------------
    $ownerEmail = "abhishektripathi0205@gmail.com";  // 🔴 CHANGE THIS TO YOUR EMAIL

    $emailSubject = "New Enquiry from Website: " . $subject;

    $emailBody = "
        <h2>New Enquiry Received</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Message:</strong><br>{$message}</p>
    ";

    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: {$name} <{$email}>" . "\r\n";
    $headers .= "Reply-To: {$email}" . "\r\n";

    // ---------------------------------
    // 4. Send email
    // ---------------------------------
    if (mail($ownerEmail, $emailSubject, $emailBody, $headers)) {
        echo "1";   // success
    } else {
        echo "0";   // failed
    }

    exit;
}
?>
