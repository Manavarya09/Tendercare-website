<?php
require_once __DIR__ . '/forms/config.php';
session_start();
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

// Rate limiting: max 5 submissions per IP per hour
$rate_limit_file = __DIR__ . '/forms/rate_limit_demo.log';
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$now = time();
$window = 3600; // 1 hour
$max_requests = 5;
$entries = [];
if (file_exists($rate_limit_file)) {
    $lines = file($rate_limit_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($entry_ip, $entry_time) = explode('|', $line);
        if ($now - (int)$entry_time < $window) {
            $entries[] = [$entry_ip, (int)$entry_time];
        }
    }
}
$recent_requests = array_filter($entries, function($e) use ($ip, $window, $now) {
    return $e[0] === $ip && ($now - $e[1]) < $window;
});
if (count($recent_requests) >= $max_requests) {
    http_response_code(429);
    exit('Too many submissions from your IP. Please try again later.');
}
$entries[] = [$ip, $now];
file_put_contents($rate_limit_file, implode("\n", array_map(function($e) { return $e[0] . '|' . $e[1]; }, $entries)) . "\n");

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
    // Server settings
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST') ?: 'mail.tendercare.ae';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER');
    $mail->Password = getenv('SMTP_PASS');
    $mail->SMTPSecure = getenv('SMTP_ENCRYPTION') ?: PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = getenv('SMTP_PORT') ?: 465;

    // Recipients
    $mail->setFrom('sales@tendercare.ae', 'Tendercare Website');
    $mail->addAddress($fields['Email']); // Send to the user
    $mail->addAddress('sales@tendercare.ae'); // Send a copy to sales
    if (filter_var($fields['Email'], FILTER_VALIDATE_EMAIL)) {
      $mail->addReplyTo($fields['Email']);
    }
    // Embed the logo
    $logoPath = __DIR__ . '/assets/img/tendercare-logo.png';
    $logoCid = 'tendercare-logo';
    $logoHtml = '';
    if (file_exists($logoPath)) {
      $logoHtml = '<img src="cid:' . $logoCid . '" alt="Tendercare Logo" width="160" style="display: block; margin: 0 auto 18px auto;">';
    }
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Thank You for Your Response';
    // Build HTML body (same as contact form)
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

    // Send to sales@tendercare.ae
    $mailSales = new PHPMailer(true);
    try {
      $mailSales->isSMTP();
      $mailSales->Host = getenv('SMTP_HOST') ?: 'mail.tendercare.ae';
      $mailSales->SMTPAuth = true;
      $mailSales->Username = getenv('SMTP_USER');
      $mailSales->Password = getenv('SMTP_PASS');
      $mailSales->SMTPSecure = getenv('SMTP_ENCRYPTION') ?: PHPMailer::ENCRYPTION_SMTPS;
      $mailSales->Port = getenv('SMTP_PORT') ?: 465;
      $mailSales->setFrom('sales@tendercare.ae', 'Tendercare Website');
      $mailSales->addAddress('sales@tendercare.ae');
      if (filter_var($fields['Email'], FILTER_VALIDATE_EMAIL)) {
        $mailSales->addReplyTo($fields['Email']);
      }
      if (file_exists($logoPath)) {
        $mailSales->addEmbeddedImage($logoPath, $logoCid);
      }
      $mailSales->isHTML(true);
      $mailSales->Subject = 'Thank You for Your Response';
      $mailSales->Body = $body;
      $mailSales->send();
    } catch (Exception $e) {
      // Optionally log error
    }

    // Send to user
    if (filter_var($fields['Email'], FILTER_VALIDATE_EMAIL)) {
      $mailUser = new PHPMailer(true);
      try {
        $mailUser->isSMTP();
        $mailUser->Host = getenv('SMTP_HOST') ?: 'mail.tendercare.ae';
        $mailUser->SMTPAuth = true;
        $mailUser->Username = getenv('SMTP_USER');
        $mailUser->Password = getenv('SMTP_PASS');
        $mailUser->SMTPSecure = getenv('SMTP_ENCRYPTION') ?: PHPMailer::ENCRYPTION_SMTPS;
        $mailUser->Port = getenv('SMTP_PORT') ?: 465;
        $mailUser->setFrom('sales@tendercare.ae', 'Tendercare Website');
        $mailUser->addAddress($fields['Email']);
        $mailUser->addReplyTo('sales@tendercare.ae');
        if (file_exists($logoPath)) {
          $mailUser->addEmbeddedImage($logoPath, $logoCid);
        }
        $mailUser->isHTML(true);
        $mailUser->Subject = 'Thank You for Your Response';
        $mailUser->Body = $body;
        $mailUser->send();
      } catch (Exception $e) {
        // Optionally log error
      }
    }
    echo 'OK';
} catch (Exception $e) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
// Security headers
header('Content-Security-Policy: default-src \'self\'; script-src \'self\' https://www.google.com https://www.gstatic.com; style-src \'self\' https://fonts.googleapis.com https://www.gstatic.com; font-src \'self\' https://fonts.gstatic.com; img-src \'self\' data:;');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
} 