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
| Helper
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
| Validate required fields
|--------------------------------------------------------------------------
*/

if ($name === '' || $email === '' || $subject === '' || $message === '') {
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
| Remove newlines from header-related values
|--------------------------------------------------------------------------
*/

$name = preg_replace('/[\r\n]+/', ' ', $name);
$email = preg_replace('/[\r\n]+/', ' ', $email);
$subject = preg_replace('/[\r\n]+/', ' ', $subject);


/*
|--------------------------------------------------------------------------
| Get Resend API key from Vercel Environment Variable
|--------------------------------------------------------------------------
*/

$resendApiKey = getenv('RESEND_API_KEY');


if (!$resendApiKey) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Email service is not configured.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Your email address
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Replace this with the email where you want to RECEIVE
| portfolio contact messages.
|
*/

$recipient = 'abhishektripathi0205@gmail.com';


/*
|--------------------------------------------------------------------------
| Sender
|--------------------------------------------------------------------------
|
| For initial testing, Resend provides onboarding@resend.dev.
|
| Once you verify your own domain in Resend, change this to:
|
| Abhishek Tripathi <hello@yourdomain.com>
|
*/

$sender = 'Portfolio <onboarding@resend.dev>';


/*
|--------------------------------------------------------------------------
| Email HTML
|--------------------------------------------------------------------------
*/

$htmlMessage = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Portfolio Contact</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:30px;">

    <div style="
        max-width:650px;
        margin:auto;
        background:#ffffff;
        padding:30px;
        border-radius:12px;
    ">

        <h2 style="margin-top:0;">
            New Portfolio Contact
        </h2>

        <p>
            Someone has contacted you through your portfolio website.
        </p>

        <hr>

        <p>
            <strong>Name:</strong><br>
            ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '
        </p>

        <p>
            <strong>Email:</strong><br>
            ' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '
        </p>

        <p>
            <strong>Subject:</strong><br>
            ' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '
        </p>

        <p>
            <strong>Message:</strong><br>
            ' . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . '
        </p>

        <hr>

        <p style="font-size:12px;color:#777;">
            Sent from Abhishek Tripathi Portfolio
        </p>

    </div>

</body>
</html>
';


/*
|--------------------------------------------------------------------------
| Prepare Resend API request
|--------------------------------------------------------------------------
*/

$payload = [
    'from' => $sender,
    'to' => [$recipient],
    'reply_to' => $email,
    'subject' => 'Portfolio Contact: ' . $subject,
    'html' => $htmlMessage
];


/*
|--------------------------------------------------------------------------
| Send request to Resend
|--------------------------------------------------------------------------
*/

$ch = curl_init('https://api.resend.com/emails');

curl_setopt_array($ch, [
    CURLOPT_POST => true,

    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $resendApiKey,
        'Content-Type: application/json'
    ],

    CURLOPT_POSTFIELDS => json_encode($payload),

    CURLOPT_TIMEOUT => 20
]);


$response = curl_exec($ch);

$curlError = curl_error($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);


/*
|--------------------------------------------------------------------------
| Handle cURL error
|--------------------------------------------------------------------------
*/

if ($response === false || $curlError !== '') {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to connect to the email service.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Decode Resend response
|--------------------------------------------------------------------------
*/

$result = json_decode($response, true);


/*
|--------------------------------------------------------------------------
| Handle Resend error
|--------------------------------------------------------------------------
*/

if ($httpCode < 200 || $httpCode >= 300) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Email could not be sent.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Success
|--------------------------------------------------------------------------
*/

echo json_encode([
    'success' => true,
    'message' => 'Thank you! Your message has been sent successfully.'
]);

exit;
