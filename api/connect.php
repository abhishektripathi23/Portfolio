<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

/*
|--------------------------------------------------------------------------
| Allow only POST requests
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests are allowed.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Helper function
|--------------------------------------------------------------------------
*/

function cleanInput(string $value): string
{
    return trim(strip_tags($value));
}


/*
|--------------------------------------------------------------------------
| Get form data
|--------------------------------------------------------------------------
*/

$name = cleanInput($_POST['name'] ?? '');

$email = trim($_POST['email'] ?? '');

$subject = cleanInput($_POST['subject'] ?? '');

$message = trim(strip_tags($_POST['message'] ?? ''));


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if (
    $name === '' ||
    $email === '' ||
    $subject === '' ||
    $message === ''
) {

    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Please fill in all required fields.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Validate email
|--------------------------------------------------------------------------
*/

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Your receiving email
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Change this to your actual email address.
|
*/

$recipient = 'YOUR_EMAIL@example.com';


/*
|--------------------------------------------------------------------------
| Prevent email header injection
|--------------------------------------------------------------------------
*/

$name = preg_replace('/[\r\n]+/', ' ', $name);

$email = preg_replace('/[\r\n]+/', ' ', $email);

$subject = preg_replace('/[\r\n]+/', ' ', $subject);


/*
|--------------------------------------------------------------------------
| Email body
|--------------------------------------------------------------------------
*/

$emailBody = "

You have received a new message from your portfolio.

--------------------------------------------------

Name:
$name

Email:
$email

Subject:
$subject

--------------------------------------------------

Message:

$message

--------------------------------------------------

Sent from:
Abhishek Tripathi Portfolio

";


/*
|--------------------------------------------------------------------------
| Email headers
|--------------------------------------------------------------------------
*/

$headers = [];

$headers[] = 'MIME-Version: 1.0';

$headers[] = 'Content-Type: text/plain; charset=UTF-8';

$headers[] = 'From: Portfolio Contact <' . $recipient . '>';

$headers[] = 'Reply-To: ' . $email;


/*
|--------------------------------------------------------------------------
| Send email
|--------------------------------------------------------------------------
*/

$sent = mail(
    $recipient,
    'Portfolio Contact: ' . $subject,
    $emailBody,
    implode("\r\n", $headers)
);


/*
|--------------------------------------------------------------------------
| Response
|--------------------------------------------------------------------------
*/

if (!$sent) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to send the message. Please email me directly.'
    ]);

    exit;
}


echo json_encode([
    'success' => true,
    'message' => 'Thank you! Your message has been sent successfully.'
]);

?>
