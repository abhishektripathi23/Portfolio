<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false,'message'=>'Only POST requests are allowed.']); exit; }
function clean($v){return trim(strip_tags((string)$v));}
$name=clean($_POST['name']??''); $email=trim((string)($_POST['email']??'')); $subject=clean($_POST['subject']??''); $message=trim(strip_tags((string)($_POST['message']??'')));
if(!$name||!$email||!$subject||!$message){http_response_code(422);echo json_encode(['success'=>false,'message'=>'Please complete all required fields.']);exit;}
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){http_response_code(422);echo json_encode(['success'=>false,'message'=>'Please enter a valid email address.']);exit;}
$recipient='abhishektripathi0205@gmail.com'; // CHANGE THIS
$safeName=preg_replace('/[\r\n]+/',' ',$name); $safeEmail=preg_replace('/[\r\n]+/',' ',$email); $safeSubject=preg_replace('/[\r\n]+/',' ',$subject);
$body="New portfolio contact message\n\nName: $safeName\nEmail: $safeEmail\nSubject: $safeSubject\n\nMessage:\n$message\n";
$server=$_SERVER['SERVER_NAME']??'localhost'; $headers="MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nFrom: Portfolio <no-reply@$server>\r\nReply-To: $safeEmail";
if(!mail($recipient,'Portfolio Contact: '.$safeSubject,$body,$headers)){http_response_code(500);echo json_encode(['success'=>false,'message'=>'Message could not be sent. Please email me directly.']);exit;}
echo json_encode(['success'=>true,'message'=>'Thanks! Your message has been sent successfully.']);
?>
