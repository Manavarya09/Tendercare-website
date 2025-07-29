<?php
// --- CONFIG AND HEADERS ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/forms/error.log');

// Create error log directory if it doesn't exist
if (!file_exists(__DIR__ . '/forms')) {
    mkdir(__DIR__ . '/forms', 0755, true);
}

// Function to log errors with timestamp
function logError($message) {
    $logFile = __DIR__ . '/forms/email_errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    error_log($logMessage, 3, $logFile);
    error_log($logMessage); // Also log to default error log
}

header('Content-Security-Policy: default-src \'self\'; script-src \'self\' https://www.google.com https://www.gstatic.com; style-src \'self\' https://fonts.googleapis.com https://www.gstatic.com; font-src \'self\' https://fonts.gstatic.com; img-src \'self\' data:;');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
}
// --- END OF CONFIG AND HEADERS ---

// Start session and load config
session_start();

try {
    // Load configuration
    $configFile = __DIR__ . '/forms/config.php';
    if (!file_exists($configFile)) {
        throw new Exception('Config file not found: ' . $configFile);
    }
    require_once $configFile;
    
    // Log environment status
    logError('Environment loaded. SMTP Host: ' . (getenv('SMTP_HOST') ?: 'Not set'));
    logError('SMTP Username: ' . (getenv('SMTP_USERNAME') ? 'Set' : 'Not set'));
    
} catch (Exception $e) {
    logError('Configuration error: ' . $e->getMessage());
    http_response_code(500);
    exit('Configuration error. Please try again later.');
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    exit('Invalid CSRF token.');
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/assets/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/assets/vendor/phpmailer/src/SMTP.php';
require_once __DIR__ . '/assets/vendor/phpmailer/src/Exception.php';

// Collect form data
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

// Sanitize and validate inputs
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

$fields = [
    'Customer Name' => clean_input(post('customerName')),
    'Organization Name' => clean_input(post('orgName')),
    'Email' => clean_input(post('contactDetails')),
    'Phone Country Code' => isset($_POST['countryCode']) ? clean_input($_POST['countryCode']) : '+971',
    'Phone Number' => clean_input(post('preferredContact')),
    'Number of Users' => clean_input(post('numUsers')),
    'Number of Doctors' => clean_input(post('numDoctors')),
    'Specialties' => clean_input(post('specialties')),
    'Number of Branches' => clean_input(post('numBranches')),
    'Inquiry Type(s)' => $inquiry_type_str,
    'Other Inquiry Text' => clean_input(post('otherInquiryText')),
    'Existing Software' => clean_input(post('existingSoftware')),
    'Server Preference' => clean_input(post('serverPref')),
    'Preferred Demo Date' => clean_input(post('demoDate')),
    'Budget' => clean_input(post('budget')),
];

// Honeypot check
if (!empty($_POST['website_hp'])) {
    http_response_code(400);
    exit('Spam detected.');
}

// Validate required fields
if (empty($fields['Customer Name']) || !preg_match('/^[\p{L} .\'-]{2,100}$/u', $fields['Customer Name'])) {
    http_response_code(400);
    exit('Invalid customer name.');
}
if (empty($fields['Organization Name']) || strlen($fields['Organization Name']) > 150) {
    http_response_code(400);
    exit('Invalid organization name.');
}
if (empty($fields['Email']) || !filter_var($fields['Email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Invalid email address.');
}
if (empty($fields['Phone Number']) || !preg_match('/^[0-9]{5,20}$/', $fields['Phone Number'])) {
    http_response_code(400);
    exit('Invalid phone number.');
}
if (!empty($fields['Number of Users']) && !preg_match('/^[0-9]{1,5}$/', $fields['Number of Users'])) {
    http_response_code(400);
    exit('Invalid number of users.');
}
if (!empty($fields['Number of Doctors']) && !preg_match('/^[0-9]{1,5}$/', $fields['Number of Doctors'])) {
    http_response_code(400);
    exit('Invalid number of doctors.');
}
if (!empty($fields['Specialties']) && strlen($fields['Specialties']) > 200) {
    http_response_code(400);
    exit('Invalid specialties.');
}
if (!empty($fields['Number of Branches']) && !preg_match('/^[0-9]{1,5}$/', $fields['Number of Branches'])) {
    http_response_code(400);
    exit('Invalid number of branches.');
}
if (!empty($fields['Other Inquiry Text']) && strlen($fields['Other Inquiry Text']) > 300) {
    http_response_code(400);
    exit('Invalid other inquiry text.');
}
if (!empty($fields['Existing Software']) && strlen($fields['Existing Software']) > 200) {
    http_response_code(400);
    exit('Invalid existing software.');
}
if (!empty($fields['Server Preference']) && strlen($fields['Server Preference']) > 50) {
    http_response_code(400);
    exit('Invalid server preference.');
}
if (!empty($fields['Preferred Demo Date']) && strlen($fields['Preferred Demo Date']) > 50) {
    http_response_code(400);
    exit('Invalid demo date.');
}
if (!empty($fields['Budget']) && strlen($fields['Budget']) > 50) {
    http_response_code(400);
    exit('Invalid budget.');
}

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
$mail->Timeout = 10;
// $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
// $mail->Debugoutput = function($str, $level) {
//     file_put_contents(__DIR__ . '/forms/smtp_debug.log', date('Y-m-d H:i:s') . " - " . $str, FILE_APPEND);
// };

try {
    // Log email attempt
    logError('Attempting to send admin email to: ' . $fields['Email']);
    
    // Server settings with error logging
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST') ?: 'mail.tendercare.ae';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USERNAME') ?: 'sales@tendercare.ae';
    $mail->Password = getenv('SMTP_PASSWORD') ?: 'Tender302025';
    $mail->SMTPSecure = getenv('SMTP_ENCRYPTION') ?: 'ssl';
    $mail->Port = getenv('SMTP_PORT') ?: 465;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];
    
    // Enable debug output
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {
        $logMessage = "PHPMailer: $str";
        logError($logMessage);
        error_log($logMessage);
    };
    
    // Log SMTP settings (without password)
    logError('SMTP Settings - Host: ' . $mail->Host . ', Port: ' . $mail->Port . 
             ', Secure: ' . $mail->SMTPSecure);

    // Recipients
    $mail->setFrom('sales@tendercare.ae', 'Tendercare Website');
    $mail->addAddress($fields['Email']); // Send to the user
    $mail->addAddress('sales@tendercare.ae'); // Send a copy to sales
    if (filter_var($fields['Email'], FILTER_VALIDATE_EMAIL)) {
      $mail->addReplyTo($fields['Email']);
    }
    
    // Content for Admin Email
    $mail->isHTML(true);
    $mail->Subject = 'New Book a Demo Submission from ' . $fields['Customer Name'];
    $mail->Body = $html;
    $mail->send();

    // Send confirmation email to user
    try {
      logError('Attempting to send confirmation email to: ' . $fields['Email']);
      $mailUser = new PHPMailer(true);
      $mailUser->Timeout = 10;
        // $mailUser->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
        // $mailUser->Debugoutput = function($str, $level) {
        //     file_put_contents(__DIR__ . '/forms/smtp_debug.log', date('Y-m-d H:i:s') . " - " . $str, FILE_APPEND);
        // };

        // Server settings from .env with error logging
        $mailUser->isSMTP();
        $mailUser->Host = getenv('SMTP_HOST') ?: 'mail.tendercare.ae';
        $mailUser->SMTPAuth = true;
        $mailUser->Username = getenv('SMTP_USERNAME') ?: 'sales@tendercare.ae';
        $mailUser->Password = getenv('SMTP_PASSWORD') ?: 'Tender302025';
        $mailUser->SMTPSecure = getenv('SMTP_ENCRYPTION') ?: 'ssl';
        $mailUser->Port = getenv('SMTP_PORT') ?: 465;
        
        // Enable debug output
        $mailUser->SMTPDebug = 2;
        $mailUser->Debugoutput = function($str, $level) {
            error_log("PHPMailer (User): $str");
        };

        // Recipient
        $mailUser->setFrom(getenv('SMTP_USER'), 'Tendercare');
        $mailUser->addAddress($fields['Email'], $fields['Customer Name']); // Send to the user
        $mailUser->addReplyTo(getenv('SMTP_USER'), 'Tendercare');

        // Build user confirmation email body
        $userHtmlBody = '<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 24px;">';
        $userHtmlBody .= '<h2 style="color: #ff6600;">Thank You For Your Demo Request!</h2>';
        $userHtmlBody .= '<p>Dear ' . htmlspecialchars($fields['Customer Name']) . ',</p>';
        $userHtmlBody .= '<p>We have received your request for a demo and will contact you shortly to schedule a convenient time. Here is a copy of your submission for your records:</p>';
        $userHtmlBody .= $html; // Re-use the table from the admin email
        $userHtmlBody .= '<p style="color: #888; font-size: 0.95em; margin-top: 18px; text-align:center;">Thank you for choosing Tendercare.</p>';
        $userHtmlBody .= '</body>';
        
        // Content for User Email
        $mailUser->isHTML(true);
        $mailUser->Subject = 'Confirmation: We\'ve Received Your Demo Request';
        $mailUser->Body    = $userHtmlBody;
        $mailUser->send();
      } catch (Exception $e) {
        // Log error but don't fail the request
        error_log("Could not send confirmation email to " . $fields['Email'] . ": " . $mailUser->ErrorInfo);
    }
    // --- End Confirmation Email ---

    echo 'OK';
} catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
    exit('Mailer Error: ' . $mail->ErrorInfo);
} 