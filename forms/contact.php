<?php
require_once __DIR__ . '/config.php';
session_start();
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    exit('Invalid CSRF token.');
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

$receiving_email_address = 'manavarya0178@gmail.com';

if (file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
    include($php_email_form);
} else {
    die('Unable to load the "PHP Email Form" Library!');
}

// Sanitize and validate inputs
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

$name = isset($_POST['name']) ? clean_input($_POST['name']) : (isset($_POST['customerName']) ? clean_input($_POST['customerName']) : '');
$email = isset($_POST['email']) ? clean_input($_POST['email']) : (isset($_POST['contactDetails']) ? clean_input($_POST['contactDetails']) : '');
$subject = isset($_POST['subject']) ? clean_input($_POST['subject']) : 'Demo Form Submission';
$message = isset($_POST['message']) ? clean_input($_POST['message']) : '';

// Honeypot check
if (!empty($_POST['website_hp'])) {
    http_response_code(400);
    exit('Spam detected.');
}

// Validate name (letters, spaces, dashes, max 100 chars)
if (empty($name) || !preg_match('/^[\p{L} .\'-]{2,100}$/u', $name)) {
    http_response_code(400);
    exit('Invalid name.');
}
// Validate email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Invalid email address.');
}
// Validate subject (max 150 chars)
if (empty($subject) || strlen($subject) > 150) {
    http_response_code(400);
    exit('Invalid subject.');
}
// Validate message (if present, max 2000 chars)
if (isset($_POST['message']) && (empty($message) || strlen($message) > 2000)) {
    http_response_code(400);
    exit('Invalid message.');
}

// Rate limiting: max 5 submissions per IP per hour
$rate_limit_file = __DIR__ . '/rate_limit_contact.log';
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

$contact = new PHP_Email_Form;
$contact->ajax = true;
$contact->to = $receiving_email_address;
$contact->from_name = $name;
$contact->from_email = $email;
$contact->subject = $subject;

// Add all POST fields to the email in a consistent format
foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        $value = implode(', ', $value);
    }
    $contact->add_message(clean_input($value), ucfirst(str_replace(['_', '-'], ' ', $key)));
}

$contact->smtp = array(
    'host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'username' => getenv('SMTP_USER'),
    'password' => getenv('SMTP_PASS'),
    'port' => getenv('SMTP_PORT') ?: '587',
    'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls'
);

echo $contact->send();
?>
