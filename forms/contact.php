<?php
// --- CONFIG AND HEADERS ---
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

header('Content-Security-Policy: default-src \'self\'; script-src \'self\' https://www.google.com https://www.gstatic.com; style-src \'self\' https://fonts.googleapis.com https://www.gstatic.com; font-src \'self\' https://fonts.gstatic.com; img-src \'self\' data:;');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
}
// --- END OF CONFIG AND HEADERS ---

require_once __DIR__ . '/config.php';
session_start();

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    exit('Invalid CSRF token.');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../assets/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../assets/vendor/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../assets/vendor/phpmailer/src/Exception.php';

// Sanitize and validate inputs
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

$name = clean_input($_POST['name'] ?? '');
$email = clean_input($_POST['email'] ?? '');
$subject = clean_input($_POST['subject'] ?? 'Contact Form Submission');
$message = clean_input($_POST['message'] ?? '');

// Validation logic here...
if (empty($name) || !preg_match('/^[\p{L} .\'-]{2,100}$/u', $name)) { exit('Invalid name.'); }
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { exit('Invalid email address.'); }
if (empty($subject) || strlen($subject) > 150) { exit('Invalid subject.'); }
if (empty($message) || strlen($message) > 2000) { exit('Invalid message.'); }

// Honeypot check
if (!empty($_POST['website_hp'])) {
    exit('Spam detected.');
}

$receiving_email_address = getenv('RECEIVING_EMAIL_ADDRESS');
if (empty($receiving_email_address)) {
    error_log("Receiving email address is not set in environment variables.");
    exit("Server configuration error.");
}

$mail = new PHPMailer(true);
$mail->Timeout = 10;
// $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
// $mail->Debugoutput = function($str, $level) {
//     file_put_contents(__DIR__ . '/smtp_debug.log', date('Y-m-d H:i:s') . " - " . $str, FILE_APPEND);
// };

try {
    // Server settings from .env
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST') ?: 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER');
    $mail->Password = getenv('SMTP_PASS');
    $mail->SMTPSecure = getenv('SMTP_ENCRYPTION') ?: 'tls';
    $mail->Port = getenv('SMTP_PORT') ?: 587;

    // Recipients
    $mail->setFrom(getenv('SMTP_USER'), $name);
    $mail->addAddress($receiving_email_address);
    $mail->addReplyTo($email, $name);

    // Build the HTML email body
    $fields = [
        'Name'    => $name,
        'Email'   => $email,
        'Subject' => $subject,
        'Message' => $message
    ];

    $htmlBody = '<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 24px;">';
    $htmlBody .= '<h2 style="color: #ff6600;">New Contact Form Submission</h2>';
    $htmlBody .= '<table cellpadding="10" cellspacing="0" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(255,102,0,0.08); border: 1.5px solid #ffb347; width: 100%; max-width: 600px; margin: 0 auto;">';

    foreach ($fields as $label => $value) {
        $htmlBody .= '<tr>';
        $htmlBody .= '<td style="background: #ffb347; color: #fff; font-weight: bold; border-radius: 8px 0 0 8px; width: 150px;">' . htmlspecialchars($label) . '</td>';
        $htmlBody .= '<td style="background: #fff6f0; color: #333; border-radius: 0 8px 8px 0;">' . nl2br(htmlspecialchars($value)) . '</td>';
        $htmlBody .= '</tr>';
    }

    $htmlBody .= '</table>';
    $htmlBody .= '<p style="color: #888; font-size: 0.95em; margin-top: 18px; text-align:center;">This message was sent from the Contact Us form on your website.</p>';
    $htmlBody .= '</body>';

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $htmlBody;
    $mail->AltBody = "You have a new message from your website contact form.\n\n"."Here are the details:\n\nName: {$name}\n\nEmail: {$email}\n\nMessage:\n{$message}";

    $mail->send();

    // --- Send Confirmation Email to User ---
    try {
        $mailUser = new PHPMailer(true);
        $mailUser->Timeout = 10;
        // $mailUser->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
        // $mailUser->Debugoutput = function($str, $level) {
        //     file_put_contents(__DIR__ . '/smtp_debug.log', date('Y-m-d H:i:s') . " - " . $str, FILE_APPEND);
        // };

        // Server settings from .env
        $mailUser->isSMTP();
        $mailUser->Host = getenv('SMTP_HOST');
        $mailUser->SMTPAuth = true;
        $mailUser->Username = getenv('SMTP_USER');
        $mailUser->Password = getenv('SMTP_PASS');
        $mailUser->SMTPSecure = getenv('SMTP_ENCRYPTION');
        $mailUser->Port = getenv('SMTP_PORT');

        // Recipient
        $mailUser->setFrom(getenv('SMTP_USER'), 'Tendercare');
        $mailUser->addAddress($email, $name); // Send to the user
        $mailUser->addReplyTo(getenv('SMTP_USER'), 'Tendercare');

        // Build user confirmation email body
        $userHtmlBody = '<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 24px;">';
        $userHtmlBody .= '<h2 style="color: #ff6600;">Thank You For Your Inquiry!</h2>';
        $userHtmlBody .= '<p>Dear ' . htmlspecialchars($name) . ',</p>';
        $userHtmlBody .= '<p>We have received your message and will get back to you as soon as possible. Here is a copy of your submission for your records:</p>';
        $userHtmlBody .= $htmlBody; // Re-use the table from the admin email
        $userHtmlBody .= '<p style="color: #888; font-size: 0.95em; margin-top: 18px; text-align:center;">Thank you for choosing Tendercare.</p>';
        $userHtmlBody .= '</body>';
        
        // Content
        $mailUser->isHTML(true);
        $mailUser->Subject = 'Confirmation: We\'ve Received Your Message';
        $mailUser->Body    = $userHtmlBody;

        $mailUser->send();
    } catch (Exception $e) {
        // Log error but don't fail the request, as the admin email was sent.
        error_log("Could not send confirmation email to {$email}: " . $mailUser->ErrorInfo);
    }
    // --- End Confirmation Email ---

    echo 'OK';
} catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
    exit("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
}
