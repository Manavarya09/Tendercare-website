<?php
// IMPORTANT: In your HTML form, add:
// <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
// <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
session_start();
$config = require __DIR__ . '/forms/config.php';

// Disable error reporting in production
if (getenv('APP_ENV') !== 'development') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// CSRF token check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }
    // Honeypot check
    if (!empty($_POST['website'])) {
        http_response_code(400);
        die('Spam detected.');
    }
}

// Generate CSRF token for form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/assets/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/assets/vendor/phpmailer/src/SMTP.php';
require_once __DIR__ . '/assets/vendor/phpmailer/src/Exception.php';

function post($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$inquiry_types = [];
$possible_inquiries = [
    'newPurchase' => 'New Purchase',
    'demoRequest' => 'Demo Request',
    'softwareUpgrade' => 'Software Upgrade',
    'integrationInquiry' => 'Integration Inquiry',
    'pricingRequest' => 'Pricing Request',
    'customizationRequest' => 'Customization Request',
    'otherInquiry' => 'Other'
];
foreach ($possible_inquiries as $key => $label) {
    if (isset($_POST[$key])) {
        $inquiry_types[] = $label;
    }
}
$inquiry_type_str = implode(', ', $inquiry_types);

$fields = [
    'Customer Name' => filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING),
    'Organization Name' => filter_input(INPUT_POST, 'orgName', FILTER_SANITIZE_STRING),
    'Email' => filter_input(INPUT_POST, 'contactDetails', FILTER_VALIDATE_EMAIL),
    'Phone Country Code' => isset($_POST['countryCode']) ? htmlspecialchars($_POST['countryCode']) : '+971',
    'Phone Number' => filter_input(INPUT_POST, 'preferredContact', FILTER_SANITIZE_STRING),
    'Number of Users' => filter_input(INPUT_POST, 'numUsers', FILTER_SANITIZE_STRING),
    'Number of Doctors' => filter_input(INPUT_POST, 'numDoctors', FILTER_SANITIZE_STRING),
    'Specialties' => filter_input(INPUT_POST, 'specialties', FILTER_SANITIZE_STRING),
    'Number of Branches' => filter_input(INPUT_POST, 'numBranches', FILTER_SANITIZE_STRING),
    'Inquiry Type(s)' => $inquiry_type_str,
    'Other Inquiry Text' => filter_input(INPUT_POST, 'otherInquiryText', FILTER_SANITIZE_STRING),
    'Existing Software' => filter_input(INPUT_POST, 'existingSoftware', FILTER_SANITIZE_STRING),
    'Server Preference' => filter_input(INPUT_POST, 'serverPref', FILTER_SANITIZE_STRING),
    'Preferred Demo Date' => filter_input(INPUT_POST, 'demoDate', FILTER_SANITIZE_STRING),
    'Budget' => filter_input(INPUT_POST, 'budget', FILTER_SANITIZE_STRING),
];

$html = '<html><body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 24px;">';
$html .= '<h2 style="color: #ff6600;">New Book a Demo Submission</h2>';
$html .= '<table cellpadding="10" cellspacing="0" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(255,102,0,0.08); border: 1.5px solid #ffb347;">';
foreach ($fields as $label => $value) {
    $html .= '<tr>';
    $html .= '<td style="background: #ffb347; color: #fff; font-weight: bold; border-radius: 8px 0 0 8px;">' . htmlspecialchars($label) . '</td>';
    $html .= '<td style="background: #fff6f0; color: #333; border-radius: 0 8px 8px 0;">' . nl2br(htmlspecialchars($value)) . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';
$html .= '<p style="color: #888; font-size: 0.95em; margin-top: 18px;">This message was sent from the Book a Demo form on your website.</p>';
$html .= '</body></html>';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_username'];
    $mail->Password = $config['smtp_password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $config['smtp_port'];
    $mail->setFrom($config['smtp_username'], 'Tendercare Website');
    $mail->addAddress($fields['Email']);
    $mail->addAddress($config['receiving_email_address']);
    if (filter_var($fields['Email'], FILTER_VALIDATE_EMAIL)) {
      $mail->addReplyTo($fields['Email']);
    }
    $logoPath = __DIR__ . '/assets/img/tendercare-logo.png';
    $logoCid = 'tendercare-logo';
    $logoHtml = '';
    if (file_exists($logoPath)) {
      $logoHtml = '<img src="cid:' . $logoCid . '" alt="Tendercare Logo" width="160" style="display: block; margin: 0 auto 18px auto;">';
    }
    $mail->isHTML(true);
    $mail->Subject = 'Thank You for Your Response';
    $body = $logoHtml;
    $body .= '<table cellpadding="10" cellspacing="0" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(255,102,0,0.08); border: 1.5px solid #ffb347; width: 100%; max-width: 600px; margin: 0 auto;">';
    foreach ($fields as $label => $value) {
      $body .= '<tr>';
      $body .= '<td style="background: #ffb347; color: #fff; font-weight: bold; border-radius: 8px 0 0 8px;">' . htmlspecialchars($label) . '</td>';
      $body .= '<td style="background: #fff6f0; color: #333; border-radius: 0 8px 8px 0;">' . nl2br(htmlspecialchars($value)) . '</td>';
      $body .= '</tr>';
    }
    $body .= '</table>';
    $body .= '<p style="color: #888; font-size: 0.95em; margin-top: 18px; text-align:center;">This message was sent from the Book a Demo form on your website.</p>';
    $mail->Body = $body;
    $mail->send();
    echo 'OK';
} catch (Exception $e) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
// To use: In your HTML form, add:
// <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
// <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
?> 